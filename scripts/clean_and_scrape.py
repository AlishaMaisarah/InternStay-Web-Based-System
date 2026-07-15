import os
import sys
import json
import re
import html
import random
import subprocess
from bs4 import BeautifulSoup

USER_AGENTS = [
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/119.0",
    "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36"
]

BOILERPLATE_PATTERNS = [
    # Company info / About us
    r"(?i)\babout (?:us|the company|our company|our team|huntsman|dxc|u mobile|dexcom|foodpanda|hilti|infineon|softinn|kpmg|acca|aurelius|sime darby|asian secrets)\b",
    r"(?i)\bdxc technology is a\b",
    r"(?i)\bsc johnson is a\b",
    r"(?i)\bsoftinn is a\b",
    r"(?i)\bmysoftinn is a\b",
    r"(?i)\bcompany description\b",
    r"(?i)\babout the company\b",
    # Equal opportunity / diversity / legal
    r"(?i)\bequal opportunity employer\b",
    r"(?i)\bemployment decision\b",
    r"(?i)\bwithout regard to\b",
    r"(?i)\brace, color, religion\b",
    r"(?i)\bgender, sexual orientation\b",
    r"(?i)\bqualified applicants will receive\b",
    r"(?i)\blegal disclaimers?\b",
    r"(?i)\bcookie notices?\b",
    r"(?i)\bcookie settings?\b",
    r"(?i)\bprivacy statements?\b",
    r"(?i)\bprivacy polic(?:y|ies)\b",
    r"(?i)\bterms of (?:use|service)\b",
    r"(?i)\binvestor relations\b",
    r"(?i)\binvestor information\b",
    # Agencies / Boilerplate
    r"(?i)\bstaffing and recruiting agencies\b",
    r"(?i)\bdoes not accept unsolicited\b",
    r"(?i)\bno agency fees\b",
    # Metadata
    r"(?i)\bposted:\s+\d+\s+(?:day|week|month|year)s?\s+ago\b",
    r"(?i)\btahap senioriti\b",
    r"(?i)\bjenis pekerjaan\b",
    r"(?i)\bbidang tugas\b",
    r"(?i)\bindustri\b",
    r"(?i)\bshow more show less\b",
    r"(?i)\bapply locations?\b",
    r"(?i)\btime type\b",
    r"(?i)\bjob requisition id\b",
    r"(?i)\bhantar terus kepada pemapar\b",
    r"(?i)\bposted on:\b",
    # Benefits / Perks / Promos
    r"(?i)\btop reasons to join us\b",
    r"(?i)\blife at u mobile\b",
    r"(?i)\bwe are passionate, innovative, trustworthy\b",
    r"(?i)\blet’s start your journey with\b",
    r"(?i)\ban award-winning organization\b",
    r"(?i)\bunbeatablecareerawaits\b",
    r"(?i)\bmedical, dental, optical\b",
    r"(?i)\bconvenient location with access to public transport\b",
    r"(?i)\bspecial employee discounts\b",
    r"(?i)\bchild parental care leave\b",
    r"(?i)\bparents? care leave\b",
    r"(?i)\bstaff line & device subsidy\b",
    r"(?i)\bsmart casual attire\b",
    r"(?i)\bonce you have applied online\b",
    r"(?i)\bshortlisted candidates will be notified\b",
    r"(?i)\bwhat’s next\b",
    r"(?i)\bwhats next\b"
]

def fetch_url(url):
    ua = random.choice(USER_AGENTS)
    # Check if we are running in Laragon/Apache and need system path fallback
    cmd = ["curl", "--noproxy", "*", "-k", "-s", "-L", "-A", ua, url]
    try:
        result = subprocess.run(cmd, capture_output=True, timeout=30)
        if result.returncode == 0:
            return result.stdout.decode('utf-8', errors='replace')
    except Exception as e:
        # Fallback to absolute curl path
        try:
            cmd[0] = r"C:\Windows\System32\curl.exe"
            result = subprocess.run(cmd, capture_output=True, timeout=30)
            if result.returncode == 0:
                return result.stdout.decode('utf-8', errors='replace')
        except Exception:
            pass
    return None

def clean_description(text):
    if not text or text.strip() in ["-", "No description available.", "No description available"]:
        return "No description available."
    
    text = html.unescape(text)
    
    # Normalize CamelCase spacing to avoid losing spaces when HTML tags are stripped
    text = re.sub(r'([a-z0-9:])([A-Z][a-z])', r'\1 \2', text)
    text = re.sub(r'(Role|Responsibilities|Requirements|Qualifications|About You|Day-to-day|Day-To-Day Activities|The Day-To-Day Activities)([A-Z])', r'\1 \2', text)
    
    # Split into paragraphs/lines
    paragraphs = [p.strip() for p in text.split('\n') if p.strip()]
    cleaned_paragraphs = []
    
    for p in paragraphs:
        # Check if paragraph matches any boilerplate pattern
        is_boilerplate = False
        for pattern in BOILERPLATE_PATTERNS:
            if re.search(pattern, p):
                is_boilerplate = True
                break
        
        # Heuristics for company intros / boilerplate paragraphs
        p_lower = p.lower()
        if p_lower.startswith("we are ") or p_lower.startswith("our mission ") or p_lower.startswith("founded in "):
            if len(p.split()) > 10:
                is_boilerplate = True
                
        # Skip small lines that are meta-junk
        if len(p.split()) <= 3 and any(k in p_lower for k in ["apply", "posted", "requisition", "locations", "contract", "duration"]):
            is_boilerplate = True
            
        if is_boilerplate:
            continue
            
        cleaned_paragraphs.append(p)
        
    result_text = "\n\n".join(cleaned_paragraphs)
    
    # Limit to ~300 words while preserving natural paragraph order
    words = result_text.split()
    if len(words) > 300:
        summary_paragraphs = []
        word_count = 0
        for p in cleaned_paragraphs:
            p_words = len(p.split())
            if word_count + p_words <= 300 or not summary_paragraphs:
                summary_paragraphs.append(p)
                word_count += p_words
            else:
                break
        result_text = "\n\n".join(summary_paragraphs)
            
    # Hard truncation if still exceeding 300 words
    words = result_text.split()
    if len(words) > 300:
        result_text = " ".join(words[:300]) + "..."
        
    # If the clean description is too short (e.g. less than 30 words), fallback to first original paragraph
    if len(result_text.split()) < 30 and len(cleaned_paragraphs) > 0:
        result_text = "\n\n".join(cleaned_paragraphs[:2])
        
    return result_text.strip()


def scrape_url_description(url):
    html_data = fetch_url(url)
    if not html_data:
        return None, "Network fetch failed"
        
    soup = BeautifulSoup(html_data, 'html.parser')
    title_str = soup.title.string if soup.title else ""
    if "Gone" in title_str or "not found" in title_str.lower() or "404" in title_str:
        return None, f"Page returned: {title_str.strip()}"
        
    raw_desc = ""
    url_lower = url.lower()
    
    # 1. LinkedIn
    if "linkedin.com" in url_lower:
        desc_el = soup.select_one(".description__text, .show-more-less-html__markup, section.description, div.description")
        if desc_el:
            raw_desc = desc_el.get_text(separator='\n')
            
    # 2. Hiredly
    elif "hiredly.com" in url_lower:
        match = soup.find("script", id="__NEXT_DATA__")
        if match:
            try:
                data = json.loads(match.string)
                props = data.get('props', {}).get('pageProps', {})
                job = props.get('job', {}) or props.get('jobDetail', {}) or props.get('initialJobDetail', {})
                if job:
                    desc_html = job.get('description', '') or ''
                    req_html = job.get('requirements', '') or ''
                    combined_html = f"<div>{desc_html}</div>\n<div>{req_html}</div>"
                    raw_desc = BeautifulSoup(combined_html, 'html.parser').get_text(separator='\n')
            except Exception as e:
                return None, f"Hiredly JSON parse error: {e}"
                
    # 3. Jobsora
    elif "jobsora.com" in url_lower:
        desc_el = soup.select_one(".c-job-description, .job-description, .c-job-details, .c-job-detail, #job-description, article, .job-detail-content")
        if desc_el:
            raw_desc = desc_el.get_text(separator='\n')
            
    if not raw_desc or len(raw_desc.strip()) < 20:
        return None, "Could not locate description element in HTML"
        
    return raw_desc, None

if __name__ == "__main__":
    # We expect either --url or --text
    if len(sys.argv) < 3:
        print(json.dumps({"status": "error", "error": "Missing arguments"}))
        sys.exit(0)
        
    mode = sys.argv[1]
    input_val = sys.argv[2]
    
    if mode == "--url":
        raw_text, err = scrape_url_description(input_val)
        if err:
            print(json.dumps({"status": "error", "error": err}))
        else:
            cleaned_text = clean_description(raw_text)
            print(json.dumps({"status": "success", "description": cleaned_text}))
    elif mode == "--text":
        cleaned_text = clean_description(input_val)
        print(json.dumps({"status": "success", "description": cleaned_text}))
    else:
        print(json.dumps({"status": "error", "error": "Invalid mode"}))
