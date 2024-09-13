FROM nginx:1.27-alpine

COPY ./docker/production/nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /app

COPY ./public ./public