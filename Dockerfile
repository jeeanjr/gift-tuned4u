FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx curl

RUN mkdir -p /run/nginx

COPY nginx.conf /etc/nginx/nginx.conf
COPY index.php /var/www/html/index.php

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"
