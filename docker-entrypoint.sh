#!/bin/bash

echo "Waiting for database to be ready..."
sleep 10

echo "Running database migrations..."
php artisan migrate --force 2>&1 || echo "Migration failed, continuing anyway..."

echo "Clearing cache..."
php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true

echo "Starting supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
