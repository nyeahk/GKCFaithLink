#!/bin/bash
# Run this script to clear all Laravel caches

php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

echo "All caches cleared successfully!"