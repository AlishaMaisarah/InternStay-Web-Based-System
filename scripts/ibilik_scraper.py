"""
iBilik.my Scraper using direct requests and BeautifulSoup
Scrapes rental listings from iBilik.my for Malaysian states
"""
import site
site.ENABLE_USER_SITE = True

import os
import sys
import json
import time
import logging
import re
from bs4 import BeautifulSoup

logging.basicConfig(level=logging.INFO, format="%(levelname)s: %(message)s")

# PATCH: Fix missing environment variables in Apache/Laragon context
if 'PROGRAMFILES' not in os.environ:
    os.environ['PROGRAMFILES'] = r'C:\Program Files'
if 'PROGRAMFILES(X86)' not in os.environ:
    os.environ['PROGRAMFILES(X86)'] = r'C:\Program Files (x86)'
if 'LOCALAPPDATA' not in os.environ:
    os.environ['LOCALAPPDATA'] = r'C:\Users\HP\AppData\Local'
if 'APPDATA' not in os.environ:
    os.environ['APPDATA'] = r'C:\Users\HP\AppData\Roaming'
if 'USERPROFILE' not in os.environ:
    os.environ['USERPROFILE'] = r'C:\Users\HP'
if 'SystemRoot' not in os.environ:
    os.environ['SystemRoot'] = r'C:\Windows'
if 'windir' not in os.environ:
    os.environ['windir'] = r'C:\Windows'
current_path = os.environ.get('PATH', '')
if r'C:\Windows\System32' not in current_path:
    os.environ['PATH'] = r'C:\Windows\System32;' + current_path

BASE_URL = "https://www.ibilik.com"

# State mapping (URL slug to display name)
STATE_MAP = {
    "kuala-lumpur": "Kuala Lumpur",
    "selangor": "Selangor",
    "johor": "Johor",
    "penang": "Penang",
    "perak": "Perak",
    "sabah": "Sabah",
    "sarawak": "Sarawak",
    "melaka": "Melaka",
    "negeri-sembilan": "Negeri Sembilan",
    "pahang": "Pahang",
    "kedah": "Kedah",
    "kelantan": "Kelantan",
    "terengganu": "Terengganu",
    "perlis": "Perlis",
}

def fetch_live_with_seleniumbase(url):
    from seleniumbase import Driver
    
    def try_fetch(is_headless):
        mode = "headless" if is_headless else "headful (visible browser)"
        logging.info(f"[iBilik] Initializing SeleniumBase UC mode ({mode}) for: {url}")
        driver = Driver(uc=True, headless=is_headless)
        try:
            # Use uc_open_with_reconnect to bypass Cloudflare Turnstile/reconnect checks
            driver.uc_open_with_reconnect(url, 5)
            
            # Debugging logs to stderr
            logging.info("--- SCRAPER DEBUG LOGS ---")
            logging.info(f"Page Title: {driver.title}")
            logging.info(f"Current URL: {driver.current_url}")
            html_data = driver.page_source
            logging.info(f"Rendered HTML (first 500 chars):\n{html_data[:500]}")
            logging.info("---------------------------")
            
            # Check if page is an anti-bot block page
            title_lower = driver.title.lower() if driver.title else ""
            html_lower = html_data.lower()
            
            is_blocked = (
                ("cloudflare" in html_lower and "ray id" in html_lower) or 
                "incapsula" in html_lower or 
                "just a moment" in title_lower or
                "attention required" in title_lower or
                "checking your browser" in html_lower or
                len(html_data) < 2000
            )
            
            if is_blocked:
                logging.warning(f"[iBilik] UC mode ({mode}) was blocked by anti-bot protection.")
                driver.quit()
                return None
                
            return html_data
        except Exception as e:
            logging.warning(f"[iBilik] UC fetch failed ({mode}): {e}")
            try:
                driver.quit()
            except:
                pass
            return None
        finally:
            try:
                driver.quit()
            except:
                pass

    # 1. Try headless first
    html_res = try_fetch(is_headless=True)
    
    # 2. If blocked, retry headful to bypass WAF
    if not html_res:
        logging.warning("[iBilik] Retrying in headful mode to bypass anti-bot protection...")
        html_res = try_fetch(is_headless=False)
        
    return html_res

def parse_html_ibilik(html, state_name, limit=20):
    soup = BeautifulSoup(html, 'html.parser')
    cards = soup.select("a[href*='/room-rentals/']") or soup.select("div.group.relative.flex.flex-col") or soup.select("a[href*='/room/'], a[href*='/unit/']")
    
    results = []
    for card in cards[:limit]:
        try:
            # URL
            anchor = card if card.name == 'a' else card.select_one("a[href*='/room-rentals/'], a[href*='/room/'], a[href*='/unit/']")
            if not anchor: continue
            source_url = anchor.get('href')
            if not source_url: continue
            if not source_url.startswith('http'): source_url = BASE_URL + source_url
            
            # Skip pagination and sub-location links
            if '/locations/' in source_url or '/page/' in source_url or source_url.endswith('/room-rentals') or source_url.endswith('/room-rentals/'):
                continue
            
            # Text
            text = card.get_text(separator=" ").strip()
            
            # Price
            price = None
            price_match = re.search(r'(?:RM|MYR)\s*([\d,]+)', text)
            if price_match: price = price_match.group(1).replace(',', '')
            
            # Name
            property_name = "Unit in " + state_name
            title_elem = card.select_one("h2, h3, .font-bold, .text-lg")
            if title_elem: property_name = title_elem.get_text().strip()
            
            # Image
            image_url = ""
            img_elem = card.select_one("img[src*='listing'], img[src*='room']") or card.select_one("img")
            if img_elem: image_url = img_elem.get('src') or img_elem.get('data-src') or ""
            
            # Bed/Bath
            bedrooms = None
            bathrooms = None
            bed_match = re.search(r'(\d+)\s*Bed', text, re.IGNORECASE)
            if bed_match: bedrooms = int(bed_match.group(1))
            bath_match = re.search(r'(\d+)\s*Bath', text, re.IGNORECASE)
            if bath_match: bathrooms = int(bath_match.group(1))

            # Determine if room is Shared Room or Single Room
            p_type = "Whole Unit" if (bedrooms and bedrooms > 0 and '/unit/' in source_url) else "Room"
            if p_type == "Room":
                desc_lower = (text or "").lower() + " " + (property_name or "").lower()
                is_shared = False
                sharing_keywords = ['share', 'sharing', 'shared', 'twin', 'roommate', 'co-living', 'coliving', 'buddy', 'room-sharing', '2 pax', 'two pax', 'triple']
                for kw in sharing_keywords:
                    if kw in desc_lower:
                        is_shared = True
                        break
                p_type = "Shared Room" if is_shared else "Single Room"

            results.append({
                "property_name": property_name[:100],
                "rent_amount": price,
                "address": state_name,
                "state": state_name,
                "city": state_name,
                "source_url": source_url,
                "image_url": image_url,
                "description": text[:500],
                "source": "iBilik",
                "bedrooms": bedrooms,
                "bathrooms": bathrooms,
                "property_type": p_type
            })
        except Exception:
            continue
            
    return results

def scrape_ibilik(state_slug, limit=20):
    state_name = STATE_MAP.get(state_slug, state_slug.title())
    
    # Live Request Flow
    url = f"{BASE_URL}/locations/malaysia/{state_slug}/room-rentals"
    
    logging.info(f"[iBilik] Starting dynamic browser scraper for state: {state_name}")
    
    # Live fetch main URL
    html = fetch_live_with_seleniumbase(url)
    if not html:
        return {
            "state": state_name,
            "state_slug": state_slug,
            "count": 0,
            "items": [],
            "error": "Failed to fetch iBilik HTML (anti-bot or network error)"
        }
    
    results = parse_html_ibilik(html, state_name, limit)
            
    return {
        "state": state_name,
        "state_slug": state_slug,
        "count": len(results),
        "items": results,
        "error": None
    }

if __name__ == "__main__":
    if len(sys.argv) < 2:
        sys.exit(1)
    state_slug = sys.argv[1]
    limit = int(sys.argv[2]) if len(sys.argv) > 2 else 20
    res = scrape_ibilik(state_slug, limit)
    print(json.dumps(res, indent=2))
    if res.get("error"):
        sys.exit(2)
