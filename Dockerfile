FROM php:8.3-cli

WORKDIR /app

# Install system + Node
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Build frontend (Vite)
RUN npm install && npm run build

# Generate APP KEY (jaga-jaga)
RUN php artisan key:generate || true

# 🔥 FIX permission (WAJIB)
RUN chmod -R 775 storage bootstrap/cache

# 🔥 CLEAR CACHE (BIAR ENV RAILWAY MASUK)
RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan route:clear
RUN php artisan view:clear

# 🔥 RUN MIGRATION (opsional tapi penting)
RUN php artisan migrate --force || true

# 🔥 JALANKAN SERVER
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]