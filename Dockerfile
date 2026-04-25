FROM php:8.3-cli

WORKDIR /app

# install dependency
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copy project
COPY . .

# install laravel deps
RUN composer install --no-dev --optimize-autoloader

# build vite
RUN npm install && npm run build

# generate key (safe)
RUN php artisan key:generate || true

# RUN SERVER (INI KUNCI 🔥)
CMD php -S 0.0.0.0:$PORT -t public