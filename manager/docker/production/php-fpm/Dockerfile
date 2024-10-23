FROM php:8.3-cli AS php-cli-builder

RUN apt-get update && apt-get install -y unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /app

COPY ./composer.json ./composer.lock ./

RUN composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader

######################

FROM php:8.3-cli AS node-builder

WORKDIR /app

COPY ./importmap.php ./

RUN php bin/console importmap:install && php bin/console sass:build

COPY ./ ./

RUN php bin/console asset-map:compile

######################

FROM php:8.3-fpm

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql opcache

COPY ./docker/production/php/default.ini /usr/localt/etc/php/conf.d/default.ini

WORKDIR /app

COPY --from=php-cli-builder /app ./
COPY --from=node-builder /app/public/assets ./public/assets
COPY ./ ./

ENV APP_ENV prod

RUN php bin/console assets:install

RUN php bin/console cache:warmup \
    && chown -R www-data:www-data ./var