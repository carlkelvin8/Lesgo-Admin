#!/bin/bash
set -e

echo "Starting queue worker..."
php artisan queue:work --tries=1 --timeout=0
