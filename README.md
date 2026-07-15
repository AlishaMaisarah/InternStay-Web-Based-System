# 🎯 InternStay Compass - Project Features & Architecture Catalog

Welcome to the **InternStay Compass** repository. This document catalogs every major function, module, and system implemented in the application, including the technologies, methods, algorithms, and components responsible.

---

## 🛠️ Implemented Features & Architecture Catalog

### 🔐 1. Authentication & Authorization (Multi-Role Portal)
* **Purpose:** Handles secure registration, login, role-based segregation (Student, Company PIC, Admin), and route authorization rules.
* **Technologies Used:** Laravel, PHP, HTML, Vanilla CSS, Bootstrap, MySQL, Blade.
* **Methods/Algorithms Used:** 
  * Bcrypt Hashing (password storage).
  * Session-based Authentication state.
  * Middleware Route Protection and Gatekeeping (Role Checks).
* **Libraries/Packages Used:** Laravel UI (Bootstrap auth templates), Laravel authentication core.
* **Files or Components Responsible:**
  * **Controllers:** [LoginController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/Auth/LoginController.php), [RegisterController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/Auth/RegisterController.php)
  * **Middleware:** [RedirectIfNotStudent.php](file:///c:/laragon/www/internstay-compass/app/Http/Middleware/RedirectIfNotStudent.php), [AdminOnly.php](file:///c:/laragon/www/internstay-compass/app/Http/Middleware/AdminOnly.php), [VerifiedCompany.php](file:///c:/laragon/www/internstay-compass/app/Http/Middleware/VerifiedCompany.php), [VerifiedStudent.php](file:///c:/laragon/www/internstay-compass/app/Http/Middleware/VerifiedStudent.php)
  * **Views:** `resources/views/auth/login.blade.php`, `resources/views/auth/register.blade.php`, `resources/views/auth/login_role.blade.php`

---

### 🌐 2. Google Sign-In & Registration (OAuth)
* **Purpose:** Allows users to register or log in securely using their Google accounts, automatically matching and linking details to existing accounts or creating a new pre-verified student profile.
* **Technologies Used:** PHP, Google OAuth 2.0 API, Laravel Socialite.
* **Methods/Algorithms Used:** 
  * OAuth 2.0 Authorization Code flow.
  * Automatic Email Verification (bypasses standard email activation since the identity is verified by Google).
* **Libraries/Packages Used:** `laravel/socialite`
* **Files or Components Responsible:**
  * **Controller:** [GoogleController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/Auth/GoogleController.php)
  * **Model:** [User.php](file:///c:/laragon/www/internstay-compass/app/Models/User.php) (stores `google_id` and `avatar` fields)

---

### 📝 3. Student Onboarding & Profile Preferences
* **Purpose:** Guides new students through a step-by-step onboarding wizard to collect their academic study program, preferred industries, and email notification frequency preferences.
* **Technologies Used:** Laravel, PHP, Bootstrap, Blade, MySQL.
* **Methods/Algorithms Used:** 
  * Multi-step Wizard Flow.
  * Database CRUD Operations.
  * **Dynamic Navigation Link Visibility Logic:** Restricts the visibility of the "Rental Accommodations" navigation link to guest users and registered users who have not completed their setup/onboarding preferences. Once onboarding is completed, the link is hidden dynamically to guide the user towards their personalized dashboard.
* **Files or Components Responsible:**
  * **Controller:** [OnboardingController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/OnboardingController.php), [UserPreferenceController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/UserPreferenceController.php)
  * **Model:** [UserPreference.php](file:///c:/laragon/www/internstay-compass/app/Models/UserPreference.php)
  * **Layouts/Views:** [template.blade.php](file:///c:/laragon/www/internstay-compass/resources/views/layouts/template.blade.php), [public.blade.php](file:///c:/laragon/www/internstay-compass/resources/views/layouts/public.blade.php), `resources/views/onboarding/welcome.blade.php`, `resources/views/onboarding/step1.blade.php`, `resources/views/onboarding/step2.blade.php`, `resources/views/preferences/edit.blade.php`

---

### 💼 4. Personalized Internship Recommendation Engine
* **Purpose:** Analyzes the student's study program (course of study) and preferred industries, computes a compatibility match percentage, and recommends matching internship listings.
* **Technologies Used:** PHP, Laravel Eloquent, MySQL, Blade.
* **Methods/Algorithms Used:**
  * **Keyword Tokenization & Stem-Aware Matching:** Parses the course name into individual root words (stems) and tests their existence in the listing text using Regex.
  * **Fuzzy String Similarity:** Uses PHP's native `similar_text()` algorithm to compute character-level similarity percentages between course titles and job titles/industries.
  * **Semantic Category Boost:** Cross-references the student's course of study with comprehensive pre-defined industry triggers (e.g., matching "quantity surveying" or "computer science" courses to relevant targets in construction or IT fields) to apply a score boost.
  * **Weighted Compatibility Score:** Combines Course Match Similarity (70% weight) and Industry Association (30% weight) to produce a combined score. Listings scoring $\ge 70\%$ are recommended.
* **Files or Components Responsible:**
  * **Controller:** [PublicInternshipController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/PublicInternshipController.php) (specifically `calculateCourseSimilarityScore()`)
  * **Service:** [UserPreferenceService.php](file:///c:/laragon/www/internstay-compass/app/Services/UserPreferenceService.php) (specifically `matchesPreferences()`)

---

### 🏠 5. Budget-Based Rental Recommendation Engine
* **Purpose:** Evaluates available accommodations against a student's specified monthly budget limit and recommends properties with a matching index score.
* **Technologies Used:** PHP, Laravel Eloquent, Blade.
* **Methods/Algorithms Used:**
  * **Price Penalty Score Formula:** Returns a $100\%$ match if the rental amount is within the student's budget. If the price exceeds the budget, it calculates a penalty based on the percentage over budget:
    $$Score = \max\left(0, 100 - \left(\frac{\text{Rent Amount} - \text{Max Budget}}{\text{Max Budget}}\right) \times 100\right)$$
    Properties with scores $\ge 60\%$ are displayed as recommended.
  * **Availability Prioritization Sorting:** Automatically places closed or occupied accommodations at the bottom of the list.
* **Files or Components Responsible:**
  * **Controller:** [PublicRentalController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/PublicRentalController.php)

---

### 🕷️ 6. Web Scraping & Ingestion System (Multi-Source Scrapers)
* **Purpose:** Automates live web scraping of internships (Hiredly, Jobsora, LinkedIn) and rental accommodation properties (PropertyGuru, iProperty, iBilik) for database population.
* **Technologies Used:** PHP, Python, Undetected ChromeDriver (Selenium UC), SeleniumBase, BeautifulSoup, Requests.
* **Methods/Algorithms Used:**
  * Browser Automation & User-Agent spoofing to bypass Cloudflare Turnstile / anti-bot mechanisms.
  * BeautifulSoup DOM parsing & data extraction.
  * Dynamic environment variable pass-through (`PATH`, `APPDATA`, `LOCALAPPDATA`) for robust execution under Apache.
* **Libraries/Packages Used:** `symfony/process` (PHP execution wrapper), Python packages: `BeautifulSoup`, `requests`, `seleniumbase`.
* **Files or Components Responsible:**
  * **Services:** [HiredlyScraper.php](file:///c:/laragon/www/internstay-compass/app/Services/HiredlyScraper.php), [PropertyScraperService.php](file:///c:/laragon/www/internstay-compass/app/Services/PropertyScraperService.php)
  * **Python Scripts:** [ibilik_scraper.py](file:///c:/laragon/www/internstay-compass/scripts/ibilik_scraper.py), [property_scraper.py](file:///c:/laragon/www/internstay-compass/scripts/property_scraper.py), [hiredly_selenium.py](file:///c:/laragon/www/internstay-compass/scripts/hiredly_selenium.py), [jobsora_scraper.py](file:///c:/laragon/www/internstay-compass/scripts/jobsora_scraper.py), [linkedin_scraper.py](file:///c:/laragon/www/internstay-compass/scripts/linkedin_scraper.py)
  * **Artisan Commands:** [ScrapeInternships.php](file:///c:/laragon/www/internstay-compass/app/Console/Commands/ScrapeInternships.php), [ScrapePropertyGuru.php](file:///c:/laragon/www/internstay-compass/app/Console/Commands/ScrapePropertyGuru.php), [ScrapeIProperty.php](file:///c:/laragon/www/internstay-compass/app/Console/Commands/ScrapeIProperty.php), [ScrapeIbilik.php](file:///c:/laragon/www/internstay-compass/app/Console/Commands/ScrapeIbilik.php)

---

### 🧹 7. Data Cleaning & Deduplication
* **Purpose:** Normalizes raw scraped text data, sanitizes HTML entities, filters junk headers, formats description blocks, and prevents duplicate entries during insertion.
* **Technologies Used:** PHP, Regex, Laravel Eloquent.
* **Methods/Algorithms Used:**
  * **Deduplication Checks:** Internships are checked by composite keys `[internship_name, company]` and rentals by `[source_url]`.
  * **Eloquent tracking:** Checks `$model->wasRecentlyCreated` to compile stats on new versus updated listings.
  * **Regex sanitization:** Cleans description blocks and removes HTML artifacts.
* **Files or Components Responsible:**
  * **Service:** [JobDescriptionFormatter.php](file:///c:/laragon/www/internstay-compass/app/Services/JobDescriptionFormatter.php)
  * **Services:** [HiredlyScraper.php](file:///c:/laragon/www/internstay-compass/app/Services/HiredlyScraper.php), [PropertyScraperService.php](file:///c:/laragon/www/internstay-compass/app/Services/PropertyScraperService.php)

---

### 🗺️ 8. Geolocation (Address-to-Coordinate Translation)
* **Purpose:** Translates textual location string data into exact numerical geographic coordinates (`latitude` and `longitude`) to support mapping and spatial radius calculations.
* **Technologies Used:** PHP, Laravel HTTP Client, OpenStreetMap Nominatim API.
* **Methods/Algorithms Used:**
  * **Multi-tiered Query Matching:** Progressively attempts to geocode using: (1) normalized full address, (2) building name + city fallback, (3) postal code-stripped address, (4) raw city + state fallback.
  * **Rate-Limit Throttling:** Calls `usleep(1000000)` to respect Nominatim's strict 1 request per second rule.
  * **Premium Local Coordinate Registry:** Pre-compiled dictionary of coordinates for major Malaysian cities and states used as a fail-safe fallback when offline or rate-limited.
* **Files or Components Responsible:**
  * **Service:** [GeocodingService.php](file:///c:/laragon/www/internstay-compass/app/Services/GeocodingService.php)
  * **Artisan Commands:** [GeocodeData.php](file:///c:/laragon/www/internstay-compass/app/Console/Commands/GeocodeData.php), [GeocodeRentals.php](file:///c:/laragon/www/internstay-compass/app/Console/Commands/GeocodeRentals.php)

---

### 📌 9. Interactive Map System & Visualization
* **Purpose:** Displays an interactive map visualizer on listing detail pages, plotting nearby accommodation options and public transit stops relative to the internship position.
* **Technologies Used:** HTML, CSS, JavaScript, Leaflet JS, OpenStreetMap Tiles.
* **Methods/Algorithms Used:** 
  * Dynamic Map Rendering.
  * Custom Layer Markers (pointhi/leaflet-color-markers: Red for jobs, Green for rentals, Blue for transit, Gold for current user location).
* **Files or Components Responsible:**
  * **Views:** `resources/views/public/internships/show.blade.php`, `resources/views/public/rentals/show.blade.php`

---

### 🛣️ 10. Routing & Navigation Engine
* **Purpose:** Plots the physical street route and calculates travel distances from a user's located position to the target internship, accommodation, or transit stop.
* **Technologies Used:** JavaScript, Leaflet JS, Leaflet Routing Machine, Google Maps Directions API.
* **Methods/Algorithms Used:** 
  * Routing path interpolation and road network waypoint matching.
  * Deep-Linking Directions routing (forwards user coordinates to external Google Maps navigation).
* **Files or Components Responsible:**
  * **Views:** `resources/views/public/internships/show.blade.php`, `resources/views/public/rentals/show.blade.php`

---

### 🚉 11. Public Transport Detection
* **Purpose:** Scans the area surrounding an internship listing for public transit stations (MRT, LRT, KTM, Monorail) within a specific radius and marks them on the interactive map.
* **Technologies Used:** JavaScript, Overpass API (OpenStreetMap data interpreter), Leaflet JS.
* **Methods/Algorithms Used:**
  * **Overpass Querying:** Dynamically fetches node objects with key `railway` and values `station|halt|stop` within a 2.5km (2500m) radius of the listing.
  * **Haversine Distance Formula (JS):** Calculates the distance on the earth's curved surface between coordinates.
* **Files or Components Responsible:**
  * **Views:** `resources/views/public/internships/show.blade.php`

---

### 🔍 12. Search, Filtering, and Query Logs
* **Purpose:** Implements user-side search panels filtering listings by title keywords, state dropdowns, monthly price range, property types, and geo-distance radius bounds, while keeping query logs.
* **Technologies Used:** Laravel, PHP, Bootstrap, Blade, MySQL.
* **Methods/Algorithms Used:**
  * SQL string matching (`LIKE`).
  * MySQL-level Haversine Formula distance calculations for spatial radius ordering.
  * Relational Database Logging.
* **Files or Components Responsible:**
  * **Controllers:** [PublicInternshipController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/PublicInternshipController.php), [PublicRentalController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/PublicRentalController.php)
  * **Model:** [UserSearchHistory.php](file:///c:/laragon/www/internstay-compass/app/Models/UserSearchHistory.php)

---

### ⭐ 13. Saved Listings (Bookmarking / Favorites)
* **Purpose:** Enables student users to bookmark specific internships and accommodations, keeping them saved in a personal folder.
* **Technologies Used:** Laravel, PHP, MySQL, Bootstrap, AJAX/JavaScript.
* **Methods/Algorithms Used:** 
  * Polymorphic Database Relationships.
* **Files or Components Responsible:**
  * **Controller:** [FavoritesController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/FavoritesController.php)
  * **Model:** [Favorite.php](file:///c:/laragon/www/internstay-compass/app/Models/Favorite.php)
  * **Views:** `resources/views/favorites/index.blade.php`

---

### ✉️ 14. Email Notification & Personalized Digest System
* **Purpose:** Automatically compiles list updates matching the student's saved preference filters, and dispatches them via daily or weekly email newsletters.
* **Technologies Used:** Laravel Mail, PHP, Gmail SMTP Server, Blade.
* **Methods/Algorithms Used:** 
  * Dynamic Preference Matching.
  * Background Mail Queue dispatching.
* **Files or Components Responsible:**
  * **Notifications:** [NewListingsNotification.php](file:///c:/laragon/www/internstay-compass/app/Notifications/NewListingsNotification.php), [CustomVerifyEmail.php](file:///c:/laragon/www/internstay-compass/app/Notifications/CustomVerifyEmail.php)
  * **Artisan Command:** [SendDailyDigest.php](file:///c:/laragon/www/internstay-compass/app/Console/Commands/SendDailyDigest.php)
  * **Controller:** [VerificationController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/Auth/VerificationController.php)

---

### 📊 15. Admin Dashboard, Company Verification, & CRUD Panel
* **Purpose:** Management center for system administrators to view system statistics, verify registered Company PICs, review manual internship postings, and perform bulk operations.
* **Technologies Used:** Laravel, PHP, Bootstrap, Blade, MySQL.
* **Methods/Algorithms Used:** 
  * SQL aggregations (`count`, `group by`).
  * Admin Review Workflow logic (Approve / Reject verification profiles with rejection reasons).
  * Bulk Delete Actions (`whereIn` operations).
* **Files or Components Responsible:**
  * **Controllers:** [AdminDashboardController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/AdminDashboardController.php), [CompanyVerificationController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/Admin/CompanyVerificationController.php), [AdminCompanyInternshipController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/Admin/AdminCompanyInternshipController.php), [InternshipController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/InternshipController.php), [RentalController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/RentalController.php), [UserController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/UserController.php)
  * **Views:** `resources/views/admin/...`

---

### 🏢 16. Company Dashboard & Self-Service Listing Portal
* **Purpose:** A self-service portal for verified Company PICs to publish, update, and manage their own internship positions.
* **Technologies Used:** Laravel, PHP, Bootstrap, Blade, MySQL.
* **Methods/Algorithms Used:** 
  * Access Control Gates.
  * Relational Model CRUD.
* **Files or Components Responsible:**
  * **Controllers:** [CompanyDashboardController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/CompanyDashboardController.php), [CompanyInternshipController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/CompanyInternshipController.php), [CompanyRegisterController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/Auth/CompanyRegisterController.php)
  * **Model:** [CompanyProfile.php](file:///c:/laragon/www/internstay-compass/app/Models/CompanyProfile.php)
  * **Views:** `resources/views/company/...`

---

### 🕵️ 17. Automated Listings Availability Checker
* **Purpose:** Periodically scans the original source URLs of active scraped listings, checks for response statuses, and scans page contents to auto-close expired/occupied items.
* **Technologies Used:** Laravel Http Client, PHP, MySQL.
* **Methods/Algorithms Used:**
  * **Target Word Boundary Regex Scans:** Filters out HTML scripts and CSS styles, then scans the remaining body text for target closed keywords.
  * **Redirect & 404 Status Checks:** Auto-flags listing as closed if the HTTP request yields a 404 not found or redirects to expired page templates.
* **Files or Components Responsible:**
  * **Artisan Command:** [CheckListingsAvailability.php](file:///c:/laragon/www/internstay-compass/app/Console/Commands/CheckListingsAvailability.php)

---

### ⭐ 18. Rating & Review System
* **Purpose:** Enables students to rate (1-5 stars) and write written reviews for internship postings and rental accommodations.
* **Technologies Used:** Laravel, PHP, Bootstrap, Blade, Vanilla CSS.
* **Methods/Algorithms Used:** Database CRUD, SQL Average calculations (`avg('rating')`).
* **Files or Components Responsible:**
  * **Controllers:** [InternshipReviewController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/InternshipReviewController.php), [AccommodationReviewController.php](file:///c:/laragon/www/internstay-compass/app/Http/Controllers/AccommodationReviewController.php)
  * **Models:** [InternshipReview.php](file:///c:/laragon/www/internstay-compass/app/Models/InternshipReview.php), [AccommodationReview.php](file:///c:/laragon/www/internstay-compass/app/Models/AccommodationReview.php)

---

### ⏰ 19. Background Jobs, Task Scheduler, & Pipeline Automation
* **Purpose:** Automates background tasks such as list scraping updates, availability checks, and email digests.
* **Technologies Used:** Laravel Console Scheduler, Artisan.
* **Methods/Algorithms Used:** 
  * Overlapping task prevention (`withoutOverlapping`).
  * Single-server execution (`onOneServer`).
* **Files or Components Responsible:**
  * **Kernel:** [Kernel.php](file:///c:/laragon/www/internstay-compass/app/Console/Kernel.php)
  * **Pipeline Command:** [RunPipeline.php](file:///c:/laragon/www/internstay-compass/app/Console/Commands/RunPipeline.php)
# InternStay-Web-Based-System
