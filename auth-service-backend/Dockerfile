FROM php:7.4-fpm

RUN docker-php-ext-install bcmath pdo_mysql

RUN useradd -u 1000 -G root,www-data laravel

USER laravel