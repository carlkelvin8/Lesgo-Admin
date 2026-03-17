#!/bin/bash

echo "Environment check:"
echo "DB_HOST: ${DB_HOST:-not set}"
echo "DB_PORT: ${DB_PORT:-not set}"
echo "DB_DATABASE: ${DB_DATABASE:-not set}"
echo "DATABASE_URL: ${DATABASE_URL:0:50}..." || echo "DATABASE_URL: not set"

# If DATABASE_URL is set but individual DB_* vars are not, parse it
if [ -z "$DB_HOST" ] && [ -n "$DATABASE_URL" ]; then
    echo ""
    echo "Parsing DATABASE_URL..."
    php -r "
    \$url = parse_url(getenv('DATABASE_URL'));
    echo 'Parsed DB_HOST: ' . (\$url['host'] ?? 'not found') . PHP_EOL;
    echo 'Parsed DB_PORT: ' . (\$url['port'] ?? 'not found') . PHP_EOL;
    echo 'Parsed DB_USER: ' . (\$url['user'] ?? 'not found') . PHP_EOL;
    echo 'Parsed DB_NAME: ' . (ltrim(\$url['path'] ?? '', '/') ?: 'not found') . PHP_EOL;
    "
fi

echo ""
echo "Waiting for database to be ready..."
for i in {1..30}; do
    if php artisan db:ping 2>/dev/null; then
        echo "Database is ready!"
        break
    fi
    echo "Attempt $i/30: Waiting for database..."
    sleep 2
done

echo ""
echo "Running database migrations..."
php artisan migrate --force 2>&1 || echo "Migration completed with warnings"

echo ""
echo "Clearing cache..."
php artisan cache:clear 2>&1 || true
php artisan config:clear 2>&1 || true

echo ""
echo "Starting Laravel development server on 0.0.0.0:80..."
exec php artisan serve --host=0.0.0.0 --port=80
