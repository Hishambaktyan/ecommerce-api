FROM php:8.3-apache

RUN apt-get update && apt-get install -y apache2

RUN docker-php-ext-install pdo pdo_mysql

RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2dismod mpm_prefork || true

RUN a2enmod mpm_prefork
RUN a2enmod rewrite

COPY . /var/www/html/

EXPOSE 80

# railway apache fix