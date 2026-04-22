FROM php:8.2-apache

RUN apt-get update && apt-get install -y git unzip
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

# MÁGICA: Muda a pasta raiz do Apache direto para a pasta "public"
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf