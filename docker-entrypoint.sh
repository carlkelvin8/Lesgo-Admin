#!/bin/bash
set -e

echo "Starting PHP-FPM in background..."
/usr/local/sbin/php-fpm --nodaemonize &
PHP_FPM_PID=$!

echo "Waiting for PHP-FPM to be ready..."
sleep 3

echo "Starting Nginx in foreground..."
exec nginx -g "daemon off;"
