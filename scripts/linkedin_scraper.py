# scripts/linkedin_scraper.py
import site
site.ENABLE_USER_SITE = True

import os
import sys
import json
import re
import time
import logging
import subprocess
from html import unescape
from bs4 import BeautifulSoup

logging.basicConfig(level=logging.INFO, format="%(levelname)s: %(message)s")

# PATCH: Fix missing environment variables in Apache/Laragon context
if 'SystemRoot' not in os.environ:
    os.environ['SystemRoot'] = r'C:\Windows'
if 'windir' not in os.environ:
    os.environ['windir'] = r'C:\Windows'
current_path = os.environ.get('PATH', '')
if r'C:\Windows\System32' not in current_path:
    os.environ['PATH'] = r'C:\Windows\System32;' + current_path

BASE_URL = "https://www.linkedin.com"

# LinkedIn search keyword mapping
CATEGORY_MAP = {
    "information-technology": "information technology",
    "engineering": "engineering",
    "business": "business accounting finance",
    "healthcare": "healthcare medical",
    "construction": "construction architecture",
    "creative": "creative design",
    "admin": "admin human resources",
}

MALAYSIA_LOCATIONS = [
    "malaysia", 
    "kuala lumpur", "selangor", "putrajaya", "labuan",
    "johor", "kedah", "kelantan", "melaka", "malacca",
    "negeri sembilan", "pahang", "perak", "perlis", 
    "pulau pinang", "penang", "sabah", "sarawak", "terengganu",
    "cyberjaya", "petaling jaya", "damansara", "subang", "shah alam", "klang", "puchong", "bangi", "kajang"
]

USER_AGENTS = [
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
]

def fetch_fallback(url):
    import random
    ua = random.choice(USER_AGENTS)
    
    commands_to_try = [
        ["curl", "--noproxy", "*", "-k", "-s", "-L", "-A", ua, url],
        [r"C:\Windows\System32\curl.exe", "--noproxy", "*", "-k", "-s", "-L", "-A", ua, url],
    ]

    for i, cmd in enumerate(commands_to_try):
        try:
            result = subprocess.run(cmd, capture_output=True, timeout=45)
            if result.returncode == 0:
                html_data = result.stdout.decode('utf-8', errors='replace')
                if len(html_data) > 500: return html_data
        except Exception:
            pass
    return None

def clean_text(text):
    if not text: return "-"
    return re.sub(r'\s+', ' ', text).strip()

def clean_text_with_newlines(text):
    if not text: return "-"
    lines = [re.sub(r'[ \t]+', ' ', line).strip() for line in text.split('\n')]
    return '\n\n'.join([line for line in lines if line]).strip()

def scrape_linkedin(category, limit):
    results = []
    
    # 1. Build Query
    # https://www.linkedin.com/jobs/search?keywords=internship+KEYWORD&location=Malaysia
    keyword = CATEGORY_MAP.get(category, category.replace("-", " "))
    query = f"internship {keyword}".replace(" ", "%20")
    search_url = f"https://www.linkedin.com/jobs/search?keywords={query}&location=Malaysia&position=1&pageNum=0"
    
    logging.info(f"Target: {search_url}")
    
    # 2. Fetch
    html_content = fetch_fallback(search_url)
    if not html_content:
        return {"items": [], "count": 0, "error": "Failed to fetch HTML"}
        
    # 3. Parse
    soup = BeautifulSoup(html_content, "html.parser")
    
    # Selectors from debug analysis 
    # li div.base-card relative w-full hover:no-underline focus:no-underline base-card--link base-search-card base-search-card--link job-search-card
    cards = soup.select("div.job-search-card")
        
    logging.info(f"Found {len(cards)} candidate cards.")
    
    for card in cards:
        if len(results) >= limit: break
        
        try:
            # Title
            title_el = card.select_one("h3.base-search-card__title")
            if not title_el: continue
            title = unescape(clean_text(title_el.get_text()))
            
            # Link
            link_el = card.select_one("a.base-card__full-link")
            link = link_el.get('href') if link_el else ""
            # Strip tracking params
            if "?" in link: link = link.split("?")[0]
                
            # Company
            company = "-"
            comp_el = card.select_one("h4.base-search-card__subtitle")
            if comp_el: company = unescape(clean_text(comp_el.get_text()))
            
            # Location
            location = "-"
            loc_el = card.select_one("span.job-search-card__location")
            if loc_el: location = unescape(clean_text(loc_el.get_text()))
            
            # Date (optional description snippet not available on card view usually)
            time_el = card.select_one("time.job-search-card__listdate")
            posted = clean_text(time_el.get_text()) if time_el else ""
            
            # Dynamically fetch full job description from public view page
            desc = ""
            if link:
                try:
                    time.sleep(0.5)  # Avoid hammering LinkedIn
                    detail_html = fetch_fallback(link)
                    if detail_html:
                        detail_soup = BeautifulSoup(detail_html, "html.parser")
                        desc_el = detail_soup.select_one(".description__text, .show-more-less-html__markup, section.description, div.description")
                        if desc_el:
                            raw_desc = unescape(clean_text_with_newlines(desc_el.get_text(separator='\n')))
                            try:
                                import sys
                                import os
                                scripts_dir = os.path.dirname(os.path.abspath(__file__))
                                if scripts_dir not in sys.path:
                                    sys.path.append(scripts_dir)
                                from clean_and_scrape import clean_description
                                desc = clean_description(raw_desc)
                            except Exception as e:
                                logging.warning(f"clean_description failed: {e}")
                                desc = raw_desc
                except Exception as e:
                    logging.warning(f"Failed to fetch detail description: {e}")

            if not desc:
                desc = f"Posted: {posted}" if posted else "No description available."
            
            # Filter Location (Standard Malaysia check)
            # LinkedIn mixes locations heavily if keyword matches remote
            loc_lower = location.lower()
            is_malaysia = any(p in loc_lower for p in MALAYSIA_LOCATIONS)
            
            if location != "-" and not is_malaysia:
                 pass # Accept for now if unsure, but usually 'Malaysia' param handles it.

            results.append({
                "title": title,
                "company": company,
                "location": location,
                "description": desc,
                "source_url": link
            })
            
        except Exception as e:
            continue
            
    return {
        "items": results,
        "count": len(results),
        "error": None
    }

if __name__ == "__main__":
    if len(sys.argv) < 3:
        category = "information-technology"
        limit = 5
    else:
        category = sys.argv[1]
        limit = int(sys.argv[2])

    data = scrape_linkedin(category, limit)
    # Ensure ASCII to prevent encoding issues on Windows console -> PHP
    print(json.dumps(data, ensure_ascii=True))
