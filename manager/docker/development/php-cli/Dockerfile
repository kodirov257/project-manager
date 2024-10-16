FROM php:8.3-cli

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

RUN apt-get update && apt-get install -y libpq-dev libzip-dev zip \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /app