FROM php:8.2-apache

# Extensões necessárias para banco de dados
RUN docker-php-ext-install pdo pdo_mysql

# Ativa mod_rewrite para o .htaccess funcionar
RUN a2enmod rewrite

# Configura o Apache para servir a partir de /public (segurança: arquivos PHP
# fora de public/ ficam inacessíveis pela web)
COPY docker/apache.conf /etc/apache2/sites-enabled/000-default.conf
