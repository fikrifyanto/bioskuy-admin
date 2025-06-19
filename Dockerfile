FROM dunglas/frankenphp

WORKDIR /app

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    php8.2-bcmath \
    php8.2-mysql \
    php8.2-mbstring \
    php8.2-tokenizer \
    php8.2-xml \
    php8.2-fileinfo \
    php8.2-curl \
    php8.2-ctype \
    php8.2-opcache \
    php8.2-session \
    php8.2-gd \
    php8.2-redis \
    php8.2-simplexml \
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
