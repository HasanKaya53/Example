FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql mysqli zip gd mbstring xml intl opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Xdebug kurulumu
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Xdebug yapılandırması
COPY ./docker_config/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

USER www-data
