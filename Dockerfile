FROM php:8.2-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libzip-dev zip unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd zip opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

COPY . .
RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data storage bootstrap/cache

ENV PORT=8080

# ponytail: artisan serve cukup utk free tier; ganti ke octane/frankenphp saat butuh concurrency
CMD php artisan migrate --force && php artisan config:cache && php artisan serve --host=0.0.0.0 --port=$PORT
