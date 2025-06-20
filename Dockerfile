FROM dunglas/frankenphp:php8.3

ENV SERVER_NAME=":80"

WORKDIR /app

COPY . /app

RUN apt update && apt install -y zip libzip-dev libicu-dev libonig-dev libxml2-dev default-mysql-client && \
    docker-php-ext-install zip intl pdo pdo_mysql && \
    docker-php-ext-enable zip intl pdo_mysql

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

RUN composer install

RUN php artisan storage:link && \
    php artisan filament:optimize && \
    php artisan config:cache && \
    php artisan view:cache


