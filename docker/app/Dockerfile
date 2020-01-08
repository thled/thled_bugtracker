FROM php:7.4.1-fpm-alpine3.11

RUN apk add --no-cache \
    zlib-dev icu-dev \
    wget git autoconf gcc g++ make \
    && docker-php-ext-install pdo_mysql zip intl

// TODO not working for php7.4!
RUN pecl install xdebug-2.8.0 \
    && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /usr/src/app
