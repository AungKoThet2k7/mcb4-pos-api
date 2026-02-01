# 1. Base image
FROM ubuntu:24.04

# 2. Disable interactive prompts
ENV DEBIAN_FRONTEND=noninteractive

# 3. Update system & install base tools
RUN apt-get update && apt-get install -y \
    software-properties-common \
    openssl \
    vim \
    curl \
    wget \
    unzip \
    git \
    supervisor \
    nginx \
    sqlite3 \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# 4. Add Ondřej PHP repository
RUN add-apt-repository ppa:ondrej/php -y

# 5. Install PHP 8.4 + Laravel required extensions
RUN apt-get update && apt-get install -y \
    php8.4 \
    php8.4-cli \
    php8.4-fpm \
    php8.4-mysql \
    php8.4-sqlite3 \
    php8.4-gd \
    php8.4-xml \
    php8.4-mbstring \
    php8.4-curl \
    php8.4-zip \
    php8.4-bcmath \
    php8.4-redis \
    && rm -rf /var/lib/apt/lists/*

# 6. Install Composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# 7. PHP upload settings (CLI + FPM)
RUN sed -i 's/upload_max_filesize = .*/upload_max_filesize = 1000M/' /etc/php/8.4/cli/php.ini \
 && sed -i 's/post_max_size = .*/post_max_size = 1000M/' /etc/php/8.4/cli/php.ini \
 && sed -i 's/upload_max_filesize = .*/upload_max_filesize = 1000M/' /etc/php/8.4/fpm/php.ini \
 && sed -i 's/post_max_size = .*/post_max_size = 1000M/' /etc/php/8.4/fpm/php.ini

# 8. Set working directory
WORKDIR /var/www

# 9. Copy project files
COPY . .

# 10. Install PHP dependencies
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# 11. Set permissions for Laravel
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# 12. Copy Nginx & Supervisor configs
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/site.conf /etc/nginx/sites-available/default
COPY docker/supervisor/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# 13. Expose HTTP port
EXPOSE 80

# 14. Start Supervisor (nginx + php-fpm + queue)
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]