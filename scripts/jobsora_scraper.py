# scripts/jobsora_scraper.py
import site
site.ENABLE_USER_SITE = True

import os
import sys
import json
import re
import time
import logging
import subprocess
import html
from bs4 import BeautifulSoup

logging.basicConfig(level=logging.INFO, format="%(levelname)s: %(message)s")

# PATCH: Fix missing environment variables in Apache/Laragon context
if 'SystemRoot' not in os.environ:
    os.environ['SystemRoot'] = r'C:\Windows'
if 'windir' not in os.environ:
    os.environ['windir'] = r'C:\Windows'
# Ensure System32 is in PATH for Winsock DLLs
current_path = os.environ.get('PATH', '')
if r'C:\Windows\System32' not in current_path:
    os.environ['PATH'] = r'C:\Windows\System32;' + current_path

BASE_URL = "https://my.jobsora.com"

# Jobsora search keyword mapping
CATEGORY_MAP = {
    "information-technology": "information technology",
    "engineering": "engineering",
    "business": "accounting finance",
    "healthcare": "healthcare",
    "construction": "civil engineering construction",
    "creative": "creative design",
    "admin": "human resources",
}


# Location Whitelist (normalized lower case)
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
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36 Edg/119.0.0.0",
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
                html = result.stdout.decode('utf-8', errors='replace')
                if len(html) > 500: return html
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

def scrape_jobsora(category, limit):
    results = []
    
    # 1. Build Query
    # Correct param is 'query', not 'q'
    keyword = CATEGORY_MAP.get(category, category.replace("-", " "))
    query = f"internship {keyword}".replace(" ", "+")
    search_url = f"{BASE_URL}/jobs?query={query}"
    
    logging.info(f"Target: {search_url}")
    
    # 2. Fetch
    html_content = fetch_fallback(search_url)
    if not html_content:
        return {"items": [], "count": 0, "error": "Failed to fetch HTML"}
        
    # 3. Parse
    soup = BeautifulSoup(html_content, "html.parser")
    
    # Locate Job Cards
    # Updated Selectors based on debug HTML
    cards = soup.select("article.js-listing-item")
    if not cards:
        # Fallback
        cards = soup.select(".c-job-item")
        
    logging.info(f"Found {len(cards)} candidate cards.")
    
    for card in cards:
        if len(results) >= limit: break
        
        try:
            # Title
            title_el = card.select_one("h2.c-job-item__title a")
            if not title_el: continue
            title = html.unescape(clean_text(title_el.get_text()))
            link = title_el.get('href')
            if link and link.startswith("/"):
                link = BASE_URL + link
                
            # Company & Location are in .c-job-item__info-item divs
            # First one is usually Company, Second is Location
            info_items = card.select(".c-job-item__info-item")
            
            company = "-"
            location = "-"
            
            if len(info_items) > 0:
                company = html.unescape(clean_text(info_items[0].get_text()))
            if len(info_items) > 1:
                location = html.unescape(clean_text(info_items[1].get_text()))
                
            # Filter Location (Standard Malaysia check)
            # Normalize location for check
            loc_lower = location.lower()
            
            # Simple whitelist check
            is_malaysia = any(p in loc_lower for p in MALAYSIA_LOCATIONS)
            
            if location != "-" and not is_malaysia:
                 pass

            # Description (Fetch full description from detail page)
            desc = ""
            if link:
                try:
                    time.sleep(0.5)
                    detail_html = fetch_fallback(link)
                    if detail_html:
                        detail_soup = BeautifulSoup(detail_html, "html.parser")
                        desc_el = detail_soup.select_one(".c-job-description, .job-description, .c-job-details, .c-job-detail, #job-description, article, .job-detail-content")
                        if desc_el:
                            raw_desc = html.unescape(clean_text_with_newlines(desc_el.get_text(separator='\n')))
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
                    logging.warning(f"Failed to fetch Jobsora detail: {e}")

            if not desc:
                # Fallback to card snippet
                desc_el = card.select_one("p.c-job-item__description")
                if desc_el:
                    desc = html.unescape(clean_text(desc_el.get_text()))
            
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

    data = scrape_jobsora(category, limit)
    # Ensure ASCII to prevent encoding issues on Windows console -> PHP
    print(json.dumps(data, ensure_ascii=True))
