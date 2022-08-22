FROM php:8.1.0-fpm-alpine
RUN apk add --no-cache curl git zlib-dev bash build-base oniguruma-dev autoconf
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
# RUN docker-php-ext-install opcache
# COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini
WORKDIR /app
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer