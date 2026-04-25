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

# 🔥 penting
RUN chmod -R 775 storage bootstrap/cache

# 🔥 penting
RUN php artisan config:cache
RUN php artisan route:cache || true
RUN php artisan view:cache

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]