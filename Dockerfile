FROM dunglas/frankenphp

WORKDIR /app

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    php-bcmath \
    php-mysql \
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
    php-redis \
    php-swoole \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

# Copy source
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Don't run migrations here!
# Run them separately during deployment or entrypoint
RUN php artisan migrate --force

EXPOSE 80
