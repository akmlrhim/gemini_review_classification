FROM php:8.2-fpm

# Install Nginx dan ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    nginx \
    zip unzip git curl \
    libzip-dev libonig-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin semua file Laravel ke /var/www
COPY . /var/www

# Set direktori kerja
WORKDIR /var/www

# Jalankan Composer
RUN composer install --no-dev --optimize-autoloader

# Ganti permission
RUN chown -R www-data:www-data /var/www

# Salin konfigurasi nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Expose port default Railway
EXPOSE 8080

# Start nginx dan php-fpm bersamaan
CMD service nginx start && php-fpm
