# scripts/hiredly_selenium.py
"""
Hiredly Scraper (Static Version)
Replaces Selenium with direct HTML fetching + JSON parsing of __NEXT_DATA__.
This eliminates "Can't find free port" errors and browser dependency issues.
"""
import site
site.ENABLE_USER_SITE = True

import os
import sys
import json
import re
import time
import urllib.request
import ssl
import logging
from urllib.parse import urljoin
import subprocess
import html

logging.basicConfig(level=logging.INFO, format="%(levelname)s: %(message)s")

# PATCH: Fix missing environment variables in Apache/Laragon context
# This resolves [Errno 11003] (Winsock fail) and 8009001d (PowerShell .NET fail)
if 'SystemRoot' not in os.environ:
    os.environ['SystemRoot'] = r'C:\Windows'
if 'windir' not in os.environ:
    os.environ['windir'] = r'C:\Windows'
# Ensure System32 is in PATH for Winsock DLLs
current_path = os.environ.get('PATH', '')
if r'C:\Windows\System32' not in current_path:
    os.environ['PATH'] = r'C:\Windows\System32;' + current_path

BASE = "https://my.hiredly.com"

# Map slug to what Hiredly expects in the URL
CATEGORY_MAP = {
    "information-technology": "information-technology",
    "engineering": "engineering",
    "business": "accounting-finance",
    "healthcare": "healthcare",
    "construction": "building-construction",
    "creative": "creative",
    "admin": "admin-human-resources",
}

# Location Whitelist (Lower case for normalization)
MALAYSIA_LOCATIONS = [
    "malaysia", 
    "kuala lumpur", "selangor", "putrajaya", "labuan",
    "johor", "kedah", "kelantan", "melaka", "malacca",
    "negeri sembilan", "pahang", "perak", "perlis", 
    "pulau pinang", "penang", "sabah", "sarawak", "terengganu",
    "cyberjaya", "petaling jaya", "damansara", "subang", "shah alam", "klang", "puchong", "bangi", "kajang"
]

def clean_html_tags(text):
    if not text:
        return "-"
    # Pre-process HTML block tags to preserve structure as newlines
    text = re.sub(r'(?i)<br\s*/?>', '\n', text)
    text = re.sub(r'(?i)</p>', '\n\n', text)
    text = re.sub(r'(?i)</li>', '\n', text)
    text = re.sub(r'(?i)</div>', '\n', text)
    text = re.sub(r'<[^>]+>', ' ', text)
    lines = [re.sub(r'[ \t]+', ' ', line).strip() for line in text.split('\n')]
    return '\n\n'.join([line for line in lines if line]).strip()

def fetch_fallback(url):
    """
    Fallback: Try multiple system tools to fetch HTML if Python's urllib fails.
    Returns: (html_content, error_log_list)
    """
    commands_to_try = [
        # Standard PowerShell
        ["powershell", "-NoProfile", "-Command", f"$ProgressPreference = 'SilentlyContinue'; (Invoke-WebRequest -Uri '{url}' -UseBasicParsing -UserAgent 'Mozilla/5.0').Content"],
        # Absolute Path PowerShell
        [r"C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe", "-NoProfile", "-Command", f"$ProgressPreference = 'SilentlyContinue'; (Invoke-WebRequest -Uri '{url}' -UseBasicParsing -UserAgent 'Mozilla/5.0').Content"],
        # Curl (Windows 10+) - No Proxy, Insecure (SSL bypass)
        ["curl", "--noproxy", "*", "-k", "-s", "-L", "-A", "Mozilla/5.0", url],
        # Absolute Path Curl - No Proxy, Insecure
        [r"C:\Windows\System32\curl.exe", "--noproxy", "*", "-k", "-s", "-L", "-A", "Mozilla/5.0", url]
    ]

    errors = []
    
    for i, cmd in enumerate(commands_to_try):
        try:
            # Run without 'text=True' to get bytes, preventing UnicodeDecodeError on windows console garbage
            result = subprocess.run(cmd, capture_output=True, timeout=30)
            
            # Decode manually
            stdout_str = result.stdout.decode('utf-8', errors='replace')
            stderr_str = result.stderr.decode('utf-8', errors='replace')

            if result.returncode == 0 and stdout_str:
                # Basic validation
                if "<html" in stdout_str.lower() or "<!doctype" in stdout_str.lower():
                     return stdout_str.strip(), errors
            else:
                err_msg = f"Cmd '{cmd[0]}' returned {result.returncode}. Stderr: {stderr_str[:200]}"
                errors.append(err_msg)
                logging.warning(f"[Hiredly] Fallback #{i+1} failed: {err_msg}")
                
        except Exception as e:
            errors.append(f"Cmd '{cmd[0]}' exception: {str(e)}")
            logging.warning(f"[Hiredly] Fallback #{i+1} exception: {e}")
    
    return None, errors

def scrape_hiredly_static(category_slug, limit=10):
    # 1. Determine URL
    slug = CATEGORY_MAP.get(category_slug, category_slug)
    list_url = f"{BASE}/jobs-in-{slug}/internship"
    
    logging.info(f"[Hiredly] Fetching URL: {list_url}")
    
    # 2. Setup Request (SSL context + Headers)
    ctx = ssl.create_default_context()
    ctx.check_hostname = False
    ctx.verify_mode = ssl.CERT_NONE
    
    # Disable proxies to avoid Windows 'getaddrinfo' lags/errors
    proxy_handler = urllib.request.ProxyHandler({})
    # Pass SSL context via HTTPSHandler
    https_handler = urllib.request.HTTPSHandler(context=ctx)
    
    opener = urllib.request.build_opener(proxy_handler, https_handler)
    
    req = urllib.request.Request(
        list_url,
        headers={
            # Mimic a browser to avoid 403 blocks
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'
        }
    )

    max_retries = 3
    html_content = None
    last_error = None

    for attempt in range(max_retries):
        try:
            # opener.open does NOT accept 'context' arg, it's already in the handler
            with opener.open(req, timeout=15) as r:
                html_content = r.read().decode('utf-8')
            break # Success
        except Exception as e:
            last_error = e
            logging.warning(f"[Hiredly] Network fetch failed (attempt {attempt+1}/{max_retries}): {e}")
            time.sleep(2.0)
    
    fallback_errors = []
    if html_content is None:
        logging.warning("[Hiredly] urllib failed, trying system fallbacks (PS/Curl)...")
        html_content, fallback_errors = fetch_fallback(list_url)

    if html_content is None:
        # Join all errors for debugging
        all_errs = f"Urllib: {last_error} | Fallbacks: {'; '.join(fallback_errors)}"
        return {"error": f"Network error after {max_retries} attempts and fallbacks. Details: {all_errs}"}

    # 3. Parse __NEXT_DATA__
    match = re.search(r'<script id="__NEXT_DATA__" type="application/json">(.*?)</script>', html_content)
    if not match:
        return {"error": "Could not find job data (__NEXT_DATA__) in page source."}

    try:
        data = json.loads(match.group(1))
        # Navigate: props -> pageProps -> jobs
        jobs_raw = data.get('props', {}).get('pageProps', {}).get('jobs', [])
    except json.JSONDecodeError:
        return {"error": "Failed to decode page JSON data."}
    except Exception as e:
        return {"error": f"Error parsing job structure: {e}"}

    logging.info(f"[Hiredly] Found {len(jobs_raw)} raw jobs in JSON.")

    if not jobs_raw:
        return {
            "category": category_slug,
            "slug": slug,
            "list_url": list_url,
            "count": 0,
            "items": [],
            "error": None
        }

    # 4. Transform to our Format
    results = []
    for j in jobs_raw:
        if len(results) >= limit:
            break
            
        try:
            # Basic fields
            title = html.unescape((j.get('title') or '').strip())
            
            # Company
            company_obj = j.get('company') or {}
            company = html.unescape((company_obj.get('name') or '-').strip())
            
            # Location (prefer city/state if available)
            loc = html.unescape((j.get('location') or j.get('stateRegion') or '-').strip())
            
            # --- LOCATION FILTERING ---
            loc_lower = loc.lower()
            # If location is explicitly not in Malaysia, skip it.
            # We check if *any* of the whitelisted keywords appear in the location string.
            is_malaysia = any(place in loc_lower for place in MALAYSIA_LOCATIONS)
            
            if not is_malaysia:
                # logging.info(f"Skipping job '{title}' - Location '{loc}' not in Malaysia whitelist.")
                continue
            # --------------------------
 
            # Description
            desc = html.unescape((j.get('gptSummary') or j.get('description') or j.get('summary') or '-').strip())
            # Clean it
            desc = clean_html_tags(desc)
            try:
                import sys
                import os
                scripts_dir = os.path.dirname(os.path.abspath(__file__))
                if scripts_dir not in sys.path:
                    sys.path.append(scripts_dir)
                from clean_and_scrape import clean_description
                desc = clean_description(desc)
            except Exception as e:
                logging.warning(f"clean_description failed: {e}")
            
            # URL
            # Only construct if slug exists
            job_slug = j.get('slug')
            if job_slug:
                # The 'slug' in JSON is just the last part, e.g. "jobs-malaysia-hiredly-..."
                # We need to prepend BASE/jobs/
                # Based on the file dump example: slug="jobs-malaysia-hiredly-..."
                # Verify logic: The site usually does my.hiredly.com/jobs/<slug>
                source_url = f"{BASE}/jobs/{job_slug}"
            else:
                source_url = list_url

            results.append({
                "title": title,
                "company": company,
                "location": loc,
                "description": desc,
                "source_url": source_url
            })
            
        except Exception:
            continue

    return {
        "category": category_slug,
        "slug": slug,
        "list_url": list_url,
        "count": len(results),
        "items": results,
        "error": None
    }

def main():
    category_in = sys.argv[1] if len(sys.argv) > 1 else "information-technology"
    limit_in = int(sys.argv[2]) if len(sys.argv) > 2 else 10
    
    out = scrape_hiredly_static(category_in, limit=limit_in)
    
    print(json.dumps(out, ensure_ascii=False), flush=True)
    
    if out.get("error"):
        sys.exit(1)
    else:
        sys.exit(0)

if __name__ == "__main__":
    main()
