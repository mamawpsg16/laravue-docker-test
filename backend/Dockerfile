FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip

# Install PHP extensions


RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd

#MYSQL
# RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
#     && docker-php-ext-install pdo pdo_mysql gd

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependencies (this will create the vendor directory)
RUN composer install --no-scripts --no-autoloader

# Copy rest of the application
COPY . .

# Run composer dump-autoload
RUN composer dump-autoload --optimize

# Ensure storage and cache directories exist and set the correct permissions
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/vendor

# Expose port 9000 (used by PHP-FPM)
EXPOSE 9000

# Set the entrypoint
CMD ["php-fpm"]