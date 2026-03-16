#!/bin/bash
set -e

echo "Installing PHP extensions..."
apt-get update -qq
apt-get install -y -qq php-intl php-zip php-pgsql php-redis

echo "Running composer install with platform requirements ignored..."
composer install \
  --optimize-autoloader \
  --no-scripts \
  --no-interaction \
  --ignore-platform-req=ext-intl \
  --ignore-platform-req=ext-zip \
  --ignore-platform-req=php

echo "Installing npm dependencies..."
npm install

echo "Building frontend assets..."
npm run build

echo "Generating app key if needed..."
php artisan key:generate --force || true

echo "Build complete!"
