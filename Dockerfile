# Choisir l'image PHP avec Apache
FROM php:8.1-apache

# Installation de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Activer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql

# Installer Git, unzip, et les dépendances nécessaires
RUN apt-get update && apt-get install -y git unzip libzip-dev

# Installer les extensions PHP zip et unzip
RUN apt-get update && apt-get install -y unzip && docker-php-ext-install zip

# Copier ton code dans le répertoire du conteneur
COPY ./html /var/www/html

# Installation des dépendances de composer
WORKDIR /var/www
RUN composer install

# Donner certains droits pour uploader des images notamment
RUN chown -R www-data:www-data /var/www/html/public
