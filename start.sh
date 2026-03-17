#!/bin/sh
set -e

echo "Running migrations..."
php artisan migrate --force 2>&1 || true

echo "Clearing cache..."
php artisan cache:clear 2>&1 || true
php artisan config:clear 2>&1 || true

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx..."
exec nginx -c /var/www/html/nginx.conf -g "daemon off;"

