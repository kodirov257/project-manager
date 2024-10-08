FROM php:8.3-cli AS BUILDER

RUN apt-get update && apt-get install -y unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /app

COPY ./composer.json ./composer.lock ./

RUN composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader

FROM php:8.3-cli

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql opcache

COPY ./docker/production/php/default.ini /usr/localt/etc/php/conf.d/default.ini

WORKDIR /app

COPY --from=builder /app ./
COPY ./ ./

ENV APP_ENV prod

RUN php bin/console cache:warmup