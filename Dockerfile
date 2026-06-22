# Stage 1: Build frontend assets (Vite/Tailwind)
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Install backend dependencies (Composer)
FROM composer:2.7 AS backend
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --no-scripts
COPY . .
RUN composer dump-autoload --optimize

# Stage 3: Production Image (Apache + PHP 8.3)
FROM php:8.3-apache
WORKDIR /var/www/html

# Install system dependencies & PHP extensions (termasuk untuk database PostgreSQL & MySQL)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql pdo_pgsql pgsql gd zip \
    && a2enmod rewrite

# Copy compiled frontend assets & vendor
COPY --from=frontend /app/public/build ./public/build
COPY --from=backend /app/vendor ./vendor
# Copy all other application files
COPY . .

# Set proper permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Change Apache document root to Laravel's public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Setup Start Script
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Expose port 80 for Render
EXPOSE 80

# Start the application
CMD ["/usr/local/bin/start.sh"]
