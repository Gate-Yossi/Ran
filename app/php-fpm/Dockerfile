FROM php:8.2-fpm

# apt-get update & install
RUN apt-get update \
 && apt-get install -y \
            unzip

RUN pecl install redis \
 && docker-php-ext-enable redis

RUN docker-php-ext-install pdo_mysql

RUN pecl install uopz \
 && docker-php-ext-enable uopz

RUN pecl install xdebug \
 && docker-php-ext-enable xdebug

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
CMD php-fpm
RUN chown www-data:www-data /var/www/
