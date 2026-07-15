# 🚀 InternStay Compass - Terminal Commands Cheat Sheet

This document lists all the custom Artisan commands available in the **InternStay Compass** platform that you can run manually in your terminal, along with essential standard Laravel maintenance commands.

---

## 🛠️ Custom Custom Commands

### 1. Availability Checker
Verify scraped listing URLs and automatically close expired or occupied positions.
```bash
# Run the availability check for active rentals & internships
php artisan listings:check-availability
```
- **What it does**: Fetches active listings, queries their original source URL, checks for `404` errors/redirects, and scans the page content for phrases like "expired", "occupied", or "already rented". It then flags closed items in the database automatically.

---

### 2. Geocoding
Translate plain-text addresses of listings into geographic coordinates (`latitude` and `longitude`) so they can display correctly on map systems and support geolocation radius search.

```bash
# Geocode newly imported rentals (accommodation)
php artisan rentals:geocode

# Force geocode ALL rentals (even those that already have coordinates)
php artisan rentals:geocode --force

# Geocode newly imported internships
php artisan data:geocode
```

---

### 3. Data Scraping & Import
Manually trigger scrapers to ingest live listings into the system database.

```bash
# Scrape live rentals from PropertyGuru
# Syntax: scrape:propertyguru {state} {city?}
php artisan scrape:propertyguru Selangor "Seri Kembangan"
php artisan scrape:propertyguru Penang

# Scrape live rentals from iProperty
# Syntax: scrape:iproperty {state} {city?}
php artisan scrape:iproperty Selangor "Shah Alam"
php artisan scrape:iproperty Penang

# Scrape live rentals from iBilik
# Syntax: scrape:ibilik {state}
php artisan scrape:ibilik Selangor
php artisan scrape:ibilik Penang

# Scrape internship listings (Hiredly / LinkedIn / etc.)
# Syntax: internships:scrape {source=all} {category=information-technology} {limit=10}
php artisan internships:scrape hiredly information-technology 10
php artisan internships:scrape linkedin engineering 5
```

---

### 4. Full Scraping Pipeline
Run the full scraping pipeline to import both new internships and new rental accommodations in one command.
```bash
# Run the entire pipeline
php artisan app:run-pipeline

# Run a quick test (limits to 1 result per source to verify functionality)
php artisan app:run-pipeline --quick
```

---

### 5. Notification Digest Emails
Manually dispatch matched digest emails to users based on their search preferences.
```bash
# Send daily digest emails to daily-frequency users
php artisan notifications:send-daily-digest --frequency=daily

# Send weekly digest emails to weekly-frequency users
php artisan notifications:send-daily-digest --frequency=weekly

# Force send digests to all matching users (ignoring the last notified date check)
php artisan notifications:send-daily-digest --force
```

---

## 🗄️ Standard Laravel Database & Server Commands

Use these commands when managing the codebase or resetting/refreshing the system.

### Database Setup & Migrations
```bash
# Run new database migrations (run this if you add new fields or tables)
php artisan migrate

# Refresh all tables and seed database with mock/sample data
php artisan migrate:fresh --seed (DONT TOUCH)
```

### Application Cache Cleaning
Run these if you modify code or environment variables (`.env`) and don't see the changes reflected immediately:
```bash
# Clear all cached configurations, routes, and views
php artisan optimize:clear

# Cache configuration for production speed booster
php artisan config:cache
```

### Background Task Runners (Queue & Schedule)
```bash
# Run the scheduler locally in the background (polls tasks every minute)
php artisan schedule:work

# Start a queue worker to process background jobs (e.g. scrapers running in background)
php artisan queue:work
```