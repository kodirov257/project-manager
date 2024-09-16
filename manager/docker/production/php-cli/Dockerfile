FROM php:8.3-cli

RUN apt-get update && apt-get install -y unzip \
    && docker-php-ext-install opcache

COPY ./docker/production/php/default.ini /usr/localt/etc/php/conf.d/default.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /app

COPY ./composer.json ./composer.lock ./

RUN composer install --no-dev --prefer-dist --optimize-autoloader

COPY ./ ./