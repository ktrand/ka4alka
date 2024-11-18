FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql pgsql

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/backend

COPY --from=composer:2.8.2 /usr/bin/composer /usr/bin/composer

RUN chown -R www-data:www-data /var/www/backend

USER www-data

CMD ["php-fpm"] 