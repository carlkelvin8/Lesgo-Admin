#!/bin/bash
set -e

echo "Starting Laravel scheduler..."
php artisan schedule:work
