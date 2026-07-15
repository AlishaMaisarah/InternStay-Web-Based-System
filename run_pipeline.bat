@echo off
title InternStay Master Pipeline - Scraping, Geocoding, Notifications
echo ==========================================================
echo       InternStay Automation Pipeline (Local/Laragon)
echo ==========================================================
echo.
echo This will run:
echo 1. Scrape iBilik (Rental)
echo 2. Scrape PropertyGuru (Rental)
echo 3. Scrape Hiredly (Internship)
echo 4. Geocode new listings (Coordinates)
echo 5. Send Daily Digest (Emails)
echo.
pause

echo.
echo [*] Running Artisan Pipeline...
php artisan app:run-pipeline

echo.
echo ==========================================================
echo       PIPELINE FINISHED SUCCESSFULLY!
echo ==========================================================
echo.
pause
