FROM serversideup/php:8.2-fpm-nginx

WORKDIR /var/www/html

COPY . .

RUN composer install --optimize-autoloader --no-scripts --no-interaction --ignore-platform-req=ext-intl --ignore-platform-req=ext-zip --ignore-platform-req=php

RUN npm install && npm run build

RUN php artisan key:generate --force || true

EXPOSE 80

CMD ["start-container"]
