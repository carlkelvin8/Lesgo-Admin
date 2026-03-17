#!/bin/bash

echo "Waiting for database to be ready..."
sleep 10

echo "Running database migrations..."
php artisan migrate --force

echo "Clearing cache..."
php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true

echo "Starting PHP-FPM and Nginx..."
php-fpm -D
nginx -g "daemon off;"
