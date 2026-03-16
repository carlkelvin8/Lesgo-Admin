#!/bin/bash
set -e

echo "Waiting for database to be ready..."
sleep 5

echo "Running database migrations..."
php artisan migrate --force

echo "Clearing cache..."
php artisan config:cache || true
php artisan route:cache || true

echo "Starting supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
