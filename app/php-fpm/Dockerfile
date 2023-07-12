FROM php:8.2-fpm

# apt-get update & install
RUN apt-get update

RUN pecl install redis \
 && docker-php-ext-enable redis

RUN docker-php-ext-install pdo_mysql

WORKDIR /var/www
CMD php-fpm
RUN chown www-data:www-data /var/www/
