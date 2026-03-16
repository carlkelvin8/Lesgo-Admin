#!/bin/bash
set -e

echo "Installing PHP extensions..."
apt-get update
apt-get install -y php-intl php-zip

echo "Running composer install with platform requirements ignored..."
composer install --optimize-autoloader --no-scripts --no-interaction --ignore-platform-req=ext-intl --ignore-platform-req=ext-zip

echo "Running Laravel migrations..."
php artisan migrate --force

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "App initialization complete!"
