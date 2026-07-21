FROM php:8.3-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN rm -f /etc/apache2/mods-enabled/mpm_event.load
RUN rm -f /etc/apache2/mods-enabled/mpm_event.conf
RUN rm -f /etc/apache2/mods-enabled/mpm_worker.load
RUN rm -f /etc/apache2/mods-enabled/mpm_worker.conf

RUN a2enmod mpm_prefork rewrite

COPY . /var/www/html/

EXPOSE 80
