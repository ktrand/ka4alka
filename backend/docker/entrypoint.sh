#!/bin/sh

cd /var/www/backend

# Кэширование конфигурации
php bin/console cache:clear
php bin/console doctrine:migrations:migrate --no-interaction

# Запуск Supervisor
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
