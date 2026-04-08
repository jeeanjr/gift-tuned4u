FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx curl && \
    mkdir -p /run/nginx && \
    mkdir -p /var/www/html

COPY index.php /var/www/html/index.php

RUN chown -R www-data:www-data /var/www/html && \
    echo 'events {}' > /etc/nginx/nginx.conf && \
    echo 'http {' >> /etc/nginx/nginx.conf && \
    echo '  include /etc/nginx/mime.types;' >> /etc/nginx/nginx.conf && \
    echo '  server {' >> /etc/nginx/nginx.conf && \
    echo '    listen 80; root /var/www/html; index index.php;' >> /etc/nginx/nginx.conf && \
    echo '    location ~ ^/([a-zA-Z0-9_-]+)$ { try_files $uri /index.php?id=$1; }' >> /etc/nginx/nginx.conf && \
    echo '    location / { try_files $uri /index.php$is_args$args; }' >> /etc/nginx/nginx.conf && \
    echo '    location ~ \.php$ { fastcgi_pass 127.0.0.1:9000; fastcgi_index index.php; fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; include fastcgi_params; }' >> /etc/nginx/nginx.conf && \
    echo '  }' >> /etc/nginx/nginx.conf && \
    echo '}' >> /etc/nginx/nginx.conf

EXPOSE 80
CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"
