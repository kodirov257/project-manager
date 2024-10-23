FROM php:8.3-cli AS node-builder

WORKDIR /app

COPY ./importmap.php ./

RUN php bin/console importmap:install && php bin/console sass:build

COPY ./ ./

RUN php bin/console asset-map:compile

######################

FROM nginx:1.27-alpine

COPY ./docker/production/nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /app

COPY ./public ./public
COPY --from=node-builder /app/public/assets ./public/assets