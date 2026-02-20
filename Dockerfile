# 1. Base Image
FROM php:8.4-fpm-alpine

# 2. Install system packages
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    unzip \
    bash \
    sqlite-dev \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    freetype-dev \
    libpng-dev \
    jpeg-dev

# 3. Install PHP Extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Redis extension
RUN apk add --no-cache --virtual .build-deps \
    autoconf \
    g++ \
    make \
    linux-headers \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# 4. Install Composer
RUN curl -sS https://getcomposer.org/installer | php \
    -- --install-dir=/usr/local/bin --filename=composer

# 5. Set working directory
WORKDIR /var/www

# 6. Copy project
COPY . .

# 7. Install dependencies
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# 8. Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# 9. Copy configs
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/site.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisor/supervisor.conf /etc/supervisord.conf

# 10. Expose port
EXPOSE 80

# 11. Start Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
