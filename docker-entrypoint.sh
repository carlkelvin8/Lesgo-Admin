#!/bin/bash

echo "Environment check:"
echo "DB_HOST: ${DB_HOST:-not set}"
echo "DB_PORT: ${DB_PORT:-not set}"
echo "DB_DATABASE: ${DB_DATABASE:-not set}"
echo "DATABASE_URL: ${DATABASE_URL:0:50}..." || echo "DATABASE_URL: not set"

echo ""
echo "Attempting database migrations (non-blocking)..."
timeout 15 php artisan migrate --force 2>&1 || echo "Migrations skipped or failed - app will start anyway"

echo ""
echo "Clearing cache..."
php artisan cache:clear 2>&1 || true
php artisan config:clear 2>&1 || true

echo ""
echo "Starting Laravel development server on 0.0.0.0:80..."
exec php artisan serve --host=0.0.0.0 --port=80
