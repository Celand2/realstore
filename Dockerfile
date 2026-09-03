FROM node:20-alpine AS frontend

WORKDIR /var/www
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM composer:2 AS dependencies

WORKDIR /var/www
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-progress --no-dev --no-scripts --optimize-autoloader
COPY . .

FROM php:8.3-fpm

RUN docker-php-ext-install pdo_mysql

WORKDIR /var/www
COPY --from=dependencies /usr/bin/composer /usr/bin/composer
COPY --from=dependencies /var/www /var/www
COPY --from=frontend /var/www/public/build /var/www/public/build

RUN chown -R www-data:www-data storage bootstrap/cache

CMD ["php-fpm"]