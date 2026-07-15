import os
import sys
import time
import json
import argparse
import re
from bs4 import BeautifulSoup

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

def fetch_live_with_seleniumbase(url):
    from seleniumbase import Driver
    
    def try_fetch(is_headless):
        mode = "headless" if is_headless else "headful (visible browser)"
        print(f"Initializing SeleniumBase UC mode ({mode}) for: {url}", file=sys.stderr)
        driver = Driver(uc=True, headless=is_headless)
        try:
            # Use uc_open_with_reconnect to bypass Cloudflare Turnstile/reconnect checks
            driver.uc_open_with_reconnect(url, 5)
            
            # Debugging logs to stderr
            print("--- SCRAPER DEBUG LOGS ---", file=sys.stderr)
            print(f"Page Title: {driver.title}", file=sys.stderr)
            print(f"Current URL: {driver.current_url}", file=sys.stderr)
            html_data = driver.page_source
            print(f"Rendered HTML (first 500 chars):\n{html_data[:500]}", file=sys.stderr)
            print("---------------------------", file=sys.stderr)
            
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
                print(f"UC mode ({mode}) was blocked by anti-bot protection.", file=sys.stderr)
                driver.quit()
                return None
                
            return html_data
        except Exception as e:
            print(f"UC fetch failed ({mode}): {e}", file=sys.stderr)
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
        print("Retrying in headful mode to bypass anti-bot protection...", file=sys.stderr)
        html_res = try_fetch(is_headless=False)
        
    return html_res


def parse_html_pg(html, state="", city=""):
    results = []
    soup = BeautifulSoup(html, 'html.parser')
    cards = soup.select(".listing-card-v2")
    
    for card in cards:
        try:
            title_elem = card.select_one('[da-id="listing-card-v2-title"]')
            if not title_elem: continue
            title = title_elem.get_text(strip=True)
            
            url = ""
            link_elem = card.select_one("a.card-footer")
            if link_elem and link_elem.has_attr('href'):
                url = link_elem['href']
            
            price_elem = card.select_one('[da-id="listing-card-v2-price"]')
            price_text = price_elem.get_text(strip=True) if price_elem else "RM 0"
            
            address_elem = card.select_one(".listing-address")
            address = f"{city}, {state}"
            if address_elem:
                addr_text = address_elem.get_text(strip=True)
                if addr_text and addr_text != "-":
                    address = f"{addr_text}, {city}, {state}"
            
            bedrooms = None
            bathrooms = None
            features_elem = card.select_one(".listing-feature-group")
            features = features_elem.get_text(separator="\n", strip=True) if features_elem else ""
            
            if features_elem:
                info_items = features_elem.select(".info-item")
                if len(info_items) >= 1:
                    bed_text = info_items[0].get_text(strip=True)
                    try:
                        bedrooms = int(bed_text) if bed_text.isdigit() else None
                    except:
                        pass
                
                if len(info_items) >= 2:
                    bath_text = info_items[1].get_text(strip=True)
                    try:
                        bathrooms = int(bath_text) if bath_text.isdigit() else None
                    except:
                        pass
            
            if url and not url.startswith('http'):
                url = "https://www.propertyguru.com.my" + url

            image_url = ""
            img_elem = card.select_one("img[src*='propertyguru'], img[src*='img.guru'], img[data-src]")
            if not img_elem:
                img_elem = card.select_one(".gallery-container img, .gallery-img img, img")
            if img_elem:
                image_url = img_elem.get('src') or img_elem.get('data-src') or img_elem.get('srcset') or ""
                if image_url and ',' in image_url:
                    image_url = image_url.split(',')[0].strip().split(' ')[0]
                if image_url and ("image-fallback" in image_url.lower() or "fallback" in image_url.lower() or "placeholder" in image_url.lower()):
                    image_url = ""

            results.append({
                "property_name": title,
                "source_url": url,
                "rent_amount": price_text,
                "address": address,
                "bedrooms": bedrooms,
                "bathrooms": bathrooms,
                "description": features,
                "source": "PropertyGuru",
                "image_url": image_url
            })
        except Exception:
            continue
    return results

def parse_html_ip(html, state="", city=""):
    results = []
    soup = BeautifulSoup(html, 'html.parser')

    # 1. Try card-based first
    cards = soup.select('.hui-card, [data-testid="listing-card"], .listing-container')
    for card in cards:
        try:
            title_elem = card.select_one('h1, h2, h3, [class*="PropertyTitle"], .property-title')
            if not title_elem: continue
            title = title_elem.get_text(strip=True)
            
            # Deduplicate
            if any(r['property_name'] == title for r in results): continue

            url = ""
            link_elem = card.select_one('a[href*="/property/"], a[href*="/rent/"]')
            if link_elem: url = link_elem['href']
            
            price = "RM 0"
            price_elem = card.find(string=re.compile(r'RM\s?[\d,]+'))
            if price_elem: 
                price = price_elem.strip()
            else:
                p_elem = card.select_one('[class*="Price"], .property-price, .listing-price')
                if p_elem: price = p_elem.get_text(strip=True)
            
            address = f"{city}, {state}"
            addr_node = title_elem.find_next(string=True)
            if addr_node:
                addr_text = addr_node.strip()
                if addr_text and len(addr_text) > 5 and 'Contact' not in addr_text:
                    address = f"{addr_text}, {city}, {state}"
            
            # Modern iProperty icon-based bed/bath extraction
            bedrooms = None
            bathrooms = None
            
            bed_icon = card.select_one('[class*="--bed-o"], [class*="bed"]')
            if bed_icon:
                bed_parent = bed_icon.find_parent(class_="info-item") or bed_icon.parent
                if bed_parent:
                    try:
                        bedrooms = int(bed_parent.get_text(strip=True))
                    except:
                        pass
            
            bath_icon = card.select_one('[class*="--bath-o"], [class*="bath"]')
            if bath_icon:
                bath_parent = bath_icon.find_parent(class_="info-item") or bath_icon.parent
                if bath_parent:
                    try:
                        bathrooms = int(bath_parent.get_text(strip=True))
                    except:
                        pass

            # Fallback to text matching
            card_text = card.get_text()
            if bedrooms is None:
                bed_match = re.search(r'(\d+)\s*(?:Bedroom|Bed|BR)s?', card_text, re.IGNORECASE)
                if bed_match:
                    try: bedrooms = int(bed_match.group(1))
                    except: pass
            
            if bathrooms is None:
                bath_match = re.search(r'(\d+)\s*(?:Bathroom|Bath|BA)s?', card_text, re.IGNORECASE)
                if bath_match:
                    try: bathrooms = int(bath_match.group(1))
                    except: pass
            
            if url and not url.startswith('http'):
                url = "https://www.iproperty.com.my" + url

            # Image extraction: Prioritize listing images and skip agent profile pictures / logos
            image_url = ""
            # Try to grab images from the swiper gallery first
            gallery_img = card.select_one(".gallery img, .swiper-slide img, .gallery__item img, img.hui-image")
            if gallery_img:
                src = gallery_img.get('src') or gallery_img.get('data-src') or gallery_img.get('srcset') or gallery_img.get('data-original') or ""
                if src and ',' in src:
                    src = src.split(',')[0].strip().split(' ')[0]
                if src and "agent" not in src.lower() and "profile" not in src.lower() and "logo" not in src.lower():
                    image_url = src

            if not image_url:
                img_elems = card.select("img")
                for img in img_elems:
                    src = img.get('src') or img.get('data-src') or img.get('srcset') or img.get('data-original') or ""
                    if src and ',' in src:
                        src = src.split(',')[0].strip().split(' ')[0]
                    if src:
                        # Exclude obvious agent photos and logos
                        src_lower = src.lower()
                        if "agent" in src_lower or "profile" in src_lower or "logo" in src_lower or "avatar" in src_lower:
                            continue
                        # Avoid empty/tiny dummy icons
                        if "chevron" in src_lower or "clock" in src_lower:
                            continue
                        image_url = src
                        break

            # Absolute fallback to first image
            if not image_url and img_elems:
                src = img_elems[0].get('src') or img_elems[0].get('data-src') or img_elems[0].get('srcset') or ""
                if src and ',' in src:
                    src = src.split(',')[0].strip().split(' ')[0]
                image_url = src

            if image_url and ("image-fallback" in image_url.lower() or "fallback" in image_url.lower() or "placeholder" in image_url.lower()):
                image_url = ""

            # Extract detailed attributes to compile a rich description
            desc_parts = []
            if bedrooms:
                desc_parts.append(f"{bedrooms} Bedrooms")
            if bathrooms:
                desc_parts.append(f"{bathrooms} Bathrooms")
                
            # Extract other features (e.g. sqft, property type, furnishing)
            feature_items = card.select('.listing-feature-group.v2 .info-item, .listing-feature-group.v2 p')
            for f in feature_items:
                f_txt = f.get_text(strip=True)
                if f_txt and f_txt not in desc_parts and len(f_txt) < 30:
                    desc_parts.append(f_txt)

            description = " | ".join(desc_parts) if desc_parts else "Imported Listing"

            results.append({
                "property_name": title,
                "source_url": url,
                "rent_amount": price,
                "address": address,
                "bedrooms": bedrooms,
                "bathrooms": bathrooms,
                "description": description,
                "source": "iProperty",
                "image_url": image_url
            })
        except: continue

    # 2. If no cards found, use JSON-LD
    if not results:
        json_scripts = soup.find_all("script", type="application/ld+json")
        for script in json_scripts:
            try:
                if not script.string: continue
                data_list = json.loads(script.string)
                if isinstance(data_list, dict): data_list = [data_list]
                
                for data in data_list:
                    if isinstance(data, dict) and data.get("@type") == "RealEstateListing":
                        main_entity = data.get("mainEntity", {})
                        items = []
                        if isinstance(main_entity, dict):
                            items = main_entity.get("itemListElement", [])
                        
                        for item_wrapper in items:
                            item = item_wrapper.get("item", {})
                            name = item.get("spatial", {}).get("name") or item.get("name")
                            url = item.get("url")
                            if not (name and url): continue

                            if not url.startswith('http'): 
                                url = "https://www.iproperty.com.my" + url
                            
                            price = "RM 0"
                            visual_nodes = soup.find_all(string=re.compile(re.escape(name)))
                            for node in visual_nodes:
                                if node.parent.name in ['script', 'style']: continue
                                price_node = node.find_next(string=re.compile(r'RM\s?[\d,]+'))
                                if price_node:
                                    price = price_node.strip()
                                    break

                            image_url = ""
                            if isinstance(item, dict):
                                img_val = item.get("image") or item.get("photo") or item.get("thumbnailUrl")
                                if isinstance(img_val, list) and len(img_val) > 0:
                                    image_url = img_val[0]
                                elif isinstance(img_val, str):
                                    image_url = img_val
                                elif isinstance(img_val, dict):
                                    image_url = img_val.get("url") or img_val.get("contentUrl") or ""

                            if image_url and ("image-fallback" in image_url.lower() or "fallback" in image_url.lower() or "placeholder" in image_url.lower()):
                                image_url = ""

                            results.append({
                                "property_name": name,
                                "source_url": url,
                                "rent_amount": price,
                                "address": f"{name}, {city}, {state}",
                                "description": "Imported Listing",
                                "source": "iProperty",
                                "image_url": image_url
                            })
            except: continue

    return results

def scrape_propertyguru(state, city, limit=10, max_price=850):
    import urllib.parse
    state = state.strip() if state else ""
    city = city.strip() if city else ""
    query = f"{city}, {state}" if state else city
    target_url = (
        f"https://www.propertyguru.com.my/property-for-rent"
        f"?freetext={urllib.parse.quote(query)}"
        f"&market=residential"
        f"&isCommercial=false"
        f"&maxprice={max_price}"
    )
    print(f"Scraping PropertyGuru: {target_url}", file=sys.stderr)
    html = fetch_live_with_seleniumbase(target_url)
    if not html:
        print("Failed to fetch PropertyGuru HTML", file=sys.stderr)
        return None
    
    return parse_html_pg(html, state, city)

def scrape_iproperty(state, city, limit=10):
    from seleniumbase import Driver
    from selenium.webdriver.common.by import By
    
    state = state.strip() if state else ""
    city = city.strip() if city else ""
    
    base_search_url = "https://www.iproperty.com.my/property-for-rent/"
    print(f"Scraping iProperty interactively for: {city}, {state}", file=sys.stderr)
    
    driver = Driver(uc=True, headless=True)
    try:
        driver.get(base_search_url)
        time.sleep(5)
        
        # 1. Find input box
        input_box = driver.find_element(By.CSS_SELECTOR, "input[placeholder*='Search']")
        
        # 2. Click search input via JS to bypass any popup overlays
        driver.execute_script("arguments[0].click();", input_box)
        time.sleep(1)
        
        # 3. Type search query
        query = f"{city}, {state}" if state else city
        input_box.send_keys(query)
        time.sleep(3)
        
        # 4. Find all suggestions
        suggestion_selectors = [
            "li[class*='suggestion']",
            "div[class*='suggestion']",
            "li[class*='option']",
            "div[class*='option']",
            "[role='option']"
        ]
        
        suggestions = []
        unique_texts = set()
        for selector in suggestion_selectors:
            try:
                elems = driver.find_elements(By.CSS_SELECTOR, selector)
                for elem in elems:
                    text = elem.text.strip()
                    if text and text not in unique_texts:
                        suggestions.append(elem)
                        unique_texts.add(text)
            except:
                continue
                
        target_suggestion = None
        # Try to find the exact City/Area that matches our city
        for sug in suggestions:
            sug_text = sug.text.lower()
            if "city/area" in sug_text and city.lower() in sug_text:
                target_suggestion = sug
                break
                
        # Fallback 1: match suggestion containing city name
        if not target_suggestion:
            for sug in suggestions:
                sug_text = sug.text.lower()
                if city.lower() in sug_text:
                    target_suggestion = sug
                    break
                    
        # Fallback 2: grab first suggestion
        if not target_suggestion and suggestions:
            target_suggestion = suggestions[0]
            
        if target_suggestion:
            print(f"Clicking suggestion via JS: {repr(target_suggestion.text)}", file=sys.stderr)
            driver.execute_script("arguments[0].click();", target_suggestion)
            time.sleep(5)
            
        print("--- SCRAPER DEBUG LOGS ---", file=sys.stderr)
        print(f"Page Title: {driver.title}", file=sys.stderr)
        print(f"Current URL: {driver.current_url}", file=sys.stderr)
        html = driver.page_source
        print(f"Rendered HTML (first 500 chars):\n{html[:500]}", file=sys.stderr)
        print("---------------------------", file=sys.stderr)
        
        return parse_html_ip(html, state, city)
    except Exception as e:
        print(f"Interactive iProperty scrape failed: {e}", file=sys.stderr)
        return None
    finally:
        try:
            driver.quit()
        except:
            pass

def scrape_local_file_pg(file_path, state="", city=""):
    print(f"Scraping PropertyGuru from local file: {file_path}", file=sys.stderr)
    try:
        with open(file_path, "r", encoding="utf-8") as f:
            html = f.read()
        return parse_html_pg(html, state, city)
    except Exception as e:
        print(f"Failed to scrape local PG file: {e}", file=sys.stderr)
        return []

def scrape_local_file_ip(file_path, state="", city=""):
    print(f"Scraping iProperty from local file: {file_path}", file=sys.stderr)
    try:
        with open(file_path, "r", encoding="utf-8") as f:
            html = f.read()
        return parse_html_ip(html, state, city)
    except Exception as e:
        print(f"Failed to scrape local IP file: {e}", file=sys.stderr)
        return []

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description='Scrape PropertyGuru rentals')
    parser.add_argument('--state', type=str, required=True)
    parser.add_argument('--city', type=str, required=True)
    parser.add_argument('--limit', type=int, default=10)
    parser.add_argument('--maxprice', type=int, default=850)
    parser.add_argument('--source', type=str, choices=['propertyguru', 'iproperty'], default='propertyguru')
    parser.add_argument('--file', type=str, help='Path to a local HTML file to scrape')
    
    args = parser.parse_args()
    
    script_dir = os.path.dirname(os.path.abspath(__file__))
    listings = []
    
    if args.source == 'propertyguru':
        if args.file and os.path.exists(args.file):
            listings = scrape_local_file_pg(args.file, args.state, args.city)
        else:
            listings = scrape_propertyguru(args.state, args.city, args.limit, args.maxprice)
            if listings is None:
                sys.exit(2)
    else:
        # iProperty
        if args.file and os.path.exists(args.file):
            listings = scrape_local_file_ip(args.file, args.state, args.city)
        else:
            listings = scrape_iproperty(args.state, args.city, args.limit)
            if listings is None:
                sys.exit(2)

    if not listings:
        print(f"No listings found for {args.source}.", file=sys.stderr)

    print(json.dumps(listings, indent=4, ensure_ascii=True))

