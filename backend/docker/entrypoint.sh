#!/bin/sh
set -e

echo "Install dependencies..."
composer install

echo "Run migrations..."
php bin/console doctrine:migrations:migrate

echo "Waiting for RabbitMQ..."
/usr/local/bin/wait-for-it.sh rabbitmq:5672 --timeout=30 --strict -- echo "RabbitMQ is up"

echo "Setting up Messenger transports..."
php bin/console messenger:setup-transports

exec "$@"
