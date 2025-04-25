FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update

RUN apt-get install -y \
        git \
        wget \
        nano \
        libicu-dev \
        zlib1g-dev \
        libmcrypt-dev \
        sudo \
        sendmail \
        libpng-dev \
        libgeoip-dev \
        geoip-database \
        libpq-dev \
        libxml2-dev \
        zip \
        libzip-dev;

# PHP Extensions
RUN docker-php-ext-install bcmath \
		                   intl \
		                   opcache \
		                   pdo_mysql \
		                   gettext \
                           zip;

# Docker PHP Extensions Installer
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions amqp \
		                   apcu \
		                   redis;
