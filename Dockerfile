FROM dunglas/frankenphp:php8.3

ENV SERVER_NAME=":80"

WORKDIR /app

COPY . /app

RUN apt update && apt install -y zip libzip-dev libicu-dev libonig-dev libxml2-dev default-mysql-client && \
    docker-php-ext-install zip intl pdo pdo_mysql && \
    docker-php-ext-enable zip intl pdo_mysql

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

RUN composer install

RUN php artisan filament:optimize
RUN php artisan config:cache
RUN php artisan view:cache
RUN php artisan storage:link
RUN mkdir -p storage/framework/views
RUN chmod -R 777 storage
RUN php artisan migrate --force


