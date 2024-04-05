FROM php:8.2-fpm

WORKDIR /app

COPY . /app

USER root

RUN chown 1000:1000 -R /app/*
RUN chown 1000:1000 /app/.*
RUN chmod 777 -R /app/storage/*

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


EXPOSE 9000

CMD ["php-fpm"]
