FROM php:8.4.4-apache

ARG DEBIAN_FRONTEND=noninteractive

# System dependencies.
RUN apt-get update \
    && apt-get install -y sendmail libpng-dev libzip-dev zlib1g-dev libonig-dev

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
RUN mkdir -p /opt/app/public
RUN ln -s /opt/app/public /var/www/html/app
RUN chmod 777 /var/www/html

# Configure PHP.
COPY config/etc/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Composer.
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Working directory.
WORKDIR /opt/app
