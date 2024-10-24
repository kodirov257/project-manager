FROM php:8.3-cli AS assets-builder

WORKDIR /app

COPY ./importmap.php ./

RUN php bin/console importmap:install

COPY ./ ./

RUN php bin/console asset-map:compile

######################

FROM nginx:1.27-alpine

COPY ./docker/production/nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /app

COPY ./public ./public
COPY --from=assets-builder /app/public/assets ./public/assets