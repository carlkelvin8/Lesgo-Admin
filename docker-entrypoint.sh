#!/bin/bash
set -e

echo "Running database migrations..."
php artisan migrate --force || true

echo "Starting supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
