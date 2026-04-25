FROM php:8.3-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN php artisan key:generate || true

# permission penting
RUN chmod -R 775 storage bootstrap/cache

# 🔥 CLEAR CACHE
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan view:clear

# 🔥 MIGRATE DATABASE
RUN php artisan migrate --force || true

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]