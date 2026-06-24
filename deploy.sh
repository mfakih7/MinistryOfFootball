#!/bin/bash
set -e

echo "Pulling latest code..."
git pull origin main

echo "Installing composer dependencies..."
php ~/composer.phar install --optimize-autoloader --no-dev

echo "Running migrations..."
php artisan migrate --force

echo "Clearing and caching Laravel..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment completed."
