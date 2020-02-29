FROM php:7.4.2-fpm-alpine3.11

RUN apk add --no-cache \
    postgresql-dev libzip-dev icu-dev \
    bash wget git autoconf gcc g++ make \
    && docker-php-ext-install pdo_pgsql zip intl

RUN pecl install xdebug-2.9.1 \
    && docker-php-ext-enable xdebug

RUN mkdir -p /.composer && chown 1000 /.composer \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /usr/src/app
