FROM php:7.4.2-fpm-alpine3.11
MAINTAINER BP

ARG PHP_TIMEZONE

RUN apk add openssl git unzip vim postgresql-dev
RUN docker-php-ext-install pdo_pgsql

RUN ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/local/include/

RUN ln -snf /usr/share/zoneinfo/${PHP_TIMEZONE} /etc/localtime && echo ${PHP_TIMEZONE} > /etc/timezone
RUN printf '[PHP]\ndate.timezone = "%s"\n', ${PHP_TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini
RUN date

RUN sed -i 's/pm.max_children = 5.*/pm.max_children = 50/g' /usr/local/etc/php-fpm.d/www.conf
RUN sed -i 's/;catch_workers_output = yes.*/catch_workers_output = yes/g' /usr/local/etc/php-fpm.d/www.conf
RUN sed -i 's/;access.log.*/access.log = \/dev\/null/g' /usr/local/etc/php-fpm.d/www.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /srv/itse/system