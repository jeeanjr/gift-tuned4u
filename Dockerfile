FROM php:8.2-cli-alpine

RUN mkdir -p /var/www/html

COPY index.php /var/www/html/index.php

WORKDIR /var/www/html

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html"]
