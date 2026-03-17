#!/bin/bash
set -e

echo "Waiting for database to be ready..."
for i in {1..30}; do
    if php artisan db:ping 2>/dev/null; then
        echo "Database is ready!"
        break
    fi
    echo "Attempt $i/30: Waiting for database..."
    sleep 2
done

echo "Running database migrations..."
php artisan migrate --force || echo "Migration warning: some migrations may have failed"

echo "Clearing cache..."
php artisan cache:clear || true
php artisan config:clear || true

echo "Starting Laravel development server..."
exec php artisan serve --host=0.0.0.0 --port=80
