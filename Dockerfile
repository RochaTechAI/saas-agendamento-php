# Pega a imagem oficial do PHP 8.2 com o servidor Apache embutido
FROM php:8.2-apache

# Instala as extensões PDO e pdo_mysql (Necessárias para o Banco de Dados)
RUN docker-php-ext-install pdo pdo_mysql

# Ativa o módulo de reescrita de URL do Apache (Para termos URLs bonitas no MVC)
RUN a2enmod rewrite