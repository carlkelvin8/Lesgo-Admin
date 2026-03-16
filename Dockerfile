FROM php:8.2-fpm as php

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install \
    pdo_pgsql \
    zip \
    intl \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/storage
RUN chmod -R 755 /var/www/html/bootstrap/cache

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-scripts --no-interaction --ignore-platform-req=ext-intl --ignore-platform-req=ext-zip --ignore-platform-req=php

# Install Node dependencies and build assets
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install && \
    npm run build

# Generate app key
RUN php artisan key:generate --force || true

# Run migrations
RUN php artisan migrate --force || true

# Nginx + PHP-FPM stage
FROM nginx:alpine

# Install PHP-FPM and dependencies from Alpine
RUN apk add --no-cache php82 php82-fpm php82-pdo_pgsql php82-zip php82-intl php82-mbstring php82-ctype php82-json php82-session

WORKDIR /var/www/html

# Copy application files from php stage
COPY --from=php /var/www/html /var/www/html

# Configure Nginx
RUN cat > /etc/nginx/conf.d/default.conf <<'EOF'
server {
    listen 80;
    server_name _;
    root /var/www/html/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

# Create PHP-FPM config
RUN mkdir -p /etc/php82/php-fpm.d && \
    cat > /etc/php82/php-fpm.conf <<'EOF'
[global]
daemonize = no

[www]
listen = 127.0.0.1:9000
user = nobody
group = nobody
EOF

EXPOSE 80

# Start both PHP-FPM and Nginx
CMD sh -c "/usr/sbin/php-fpm82 && nginx -g 'daemon off;'"
