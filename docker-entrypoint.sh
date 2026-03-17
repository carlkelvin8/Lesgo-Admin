#!/bin/sh

echo "Running migrations..."
php artisan migrate --force 2>&1 || true

echo "Clearing cache..."
php artisan cache:clear 2>&1 || true

echo "Ready!"
