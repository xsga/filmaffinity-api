FROM php:8.3.6-apache

ARG DEBIAN_FRONTEND=noninteractive

# System dependencies.
RUN apt-get update \
    && apt-get install -y sendmail libpng-dev libzip-dev zlib1g-dev libonig-dev gcc make autoconf

# PHP libraries.
RUN pecl install apcu xdebug
RUN docker-php-ext-install mbstring zip gd pdo_mysql sockets

# Enable PHP extensions.
RUN docker-php-ext-enable apcu

# Enable Apache2 modules.
RUN a2enmod rewrite headers

# Install PHPLOC.
RUN curl -L https://phar.phpunit.de/phploc.phar > /usr/local/bin/phploc \
    && chmod +x /usr/local/bin/phploc

# Setup application folder.
RUN mkdir -p /opt/filmaffinityapi/public
RUN ln -s /opt/filmaffinityapi/public /var/www/html/filmaffinityapi

# Configure PHP.
COPY config/etc/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Composer.
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Working directory.
WORKDIR /opt/filmaffinityapi