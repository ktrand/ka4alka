FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    supervisor \
    cron \
    libsodium-dev

RUN docker-php-ext-install pdo pdo_pgsql pgsql sodium

COPY ./backend/docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/backend

COPY --from=composer:2.8.2 /usr/bin/composer /usr/bin/composer

RUN chown -R www-data:www-data /var/www/backend

ADD ./backend/docker/crontab /etc/cron.d/symfony-crontab
RUN crontab -u www-data /etc/cron.d/symfony-crontab
RUN chmod u+s /usr/sbin/cron

RUN usermod -s /bin/bash www-data


USER www-data

EXPOSE 9000

ENTRYPOINT ["/bin/sh", "/var/www/backend/docker/entrypoint.sh"]