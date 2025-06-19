# Gunakan FrankenPHP image
FROM dunglas/frankenphp:1.1.4

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apk add --no-cache \
    php-bcmath \
    php-pdo \
    php-pdo_mysql \
    php-mbstring \
    php-tokenizer \
    php-dom \
    php-simplexml \
    php-fileinfo \
    php-curl \
    php-ctype \
    php-opcache \
    php-xml \
    php-session \
    php-gd \
    php-mysqli \
    php-pecl-redis \
    php-pecl-swoole

# Copy Laravel file to container
COPY . .

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Change permission for Laravel storage dan cache
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Run migration
RUN php artisan migrate --force
