FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    postgresql-client \
    libpq-dev \
    libzip-dev \
    icu-dev \
    nginx \
    supervisor

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql \
    zip \
    intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html/storage && \
    chmod -R 755 /var/www/html/bootstrap/cache

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-scripts --no-interaction --ignore-platform-req=ext-intl --ignore-platform-req=ext-zip --ignore-platform-req=php

# Install Node dependencies and build assets
RUN apk add --no-cache nodejs npm && \
    npm install && \
    npm run build

# Configure Nginx
RUN mkdir -p /etc/nginx/sites-available /etc/nginx/sites-enabled && \
    cat > /etc/nginx/sites-available/default <<'EOF'
server {
    listen 80 default_server;
    server_name _;
    root /var/www/html/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Configure PHP-FPM
RUN mkdir -p /var/run/php && \
    cat > /usr/local/etc/php-fpm.d/www.conf <<'EOF'
[www]
user = www-data
group = www-data
listen = 127.0.0.1:9000
pm = dynamic
pm.max_children = 5
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
EOF

# Configure Supervisor
RUN mkdir -p /etc/supervisor/conf.d && \
    cat > /etc/supervisor/conf.d/services.conf <<'EOF'
[program:php-fpm]
command=/usr/local/sbin/php-fpm -F
autostart=true
autorestart=true
stderr_logfile=/var/log/php-fpm.err.log
stdout_logfile=/var/log/php-fpm.out.log

[program:nginx]
command=/usr/sbin/nginx -g "daemon off;"
autostart=true
autorestart=true
stderr_logfile=/var/log/nginx.err.log
stdout_logfile=/var/log/nginx.out.log
EOF

# Generate app key
RUN php artisan key:generate --force || true

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

CMD ["/usr/local/bin/docker-entrypoint.sh"]
