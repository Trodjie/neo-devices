FROM php:8.0-fpm

RUN apt-get update && apt-get install -y libonig-dev wget libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

COPY neo-devices /var/www/app/

WORKDIR /var/www/app/

RUN wget https://getcomposer.org/composer.phar && \
    mv composer.phar /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer && \
    composer install

COPY docker/wait_for_it.sh      /wait_for_it.sh
COPY docker/php/entrypoint.sh   /entrypoint.sh

RUN ["chmod", "+x", "/entrypoint.sh"]

ENTRYPOINT ["/entrypoint.sh"]
