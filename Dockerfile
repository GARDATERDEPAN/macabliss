FROM php:8.3-cli

WORKDIR /app

# install dependency system + node
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copy project
COPY . .

# install laravel deps
RUN composer install --no-dev --optimize-autoloader

# install & build vite
RUN npm install && npm run build

# generate key
RUN php artisan key:generate || true

# run server
CMD php -S 0.0.0.0:$PORT -t public