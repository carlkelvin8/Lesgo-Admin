#!/bin/sh

echo "Environment check:"
echo "DB_HOST: ${DB_HOST:-not set}"
echo "DB_PORT: ${DB_PORT:-not set}"
echo "DB_DATABASE: ${DB_DATABASE:-not set}"

echo ""
echo "Attempting database migrations..."
timeout 20 php artisan migrate --force 2>&1 || echo "Migrations skipped or failed"

echo ""
echo "Clearing cache..."
php artisan cache:clear 2>&1 || true
php artisan config:clear 2>&1 || true

echo ""
echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx..."
exec nginx -g "daemon off;"
