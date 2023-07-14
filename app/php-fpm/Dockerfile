FROM php:8.2-fpm

# apt-get update & install
RUN apt-get update

RUN pecl install redis \
 && docker-php-ext-enable redis

RUN docker-php-ext-install pdo_mysql

RUN pecl install xdebug \
 && docker-php-ext-enable xdebug

WORKDIR /var/www
CMD php-fpm
RUN chown www-data:www-data /var/www/
