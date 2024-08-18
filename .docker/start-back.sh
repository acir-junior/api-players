#!/bin/bash

set -e

cd /var/www/html

composer install

chmod +x /var/www/html/artisan

echo "AQUI1"
if [ ! -f ".env" ]; then
    cp .env.example .env
fi
echo "AQUI2"

php artisan migrate

echo "Executou as migrations"

php artisan route:cache &&
php artisan config:cache &&
php artisan optimize:clear &&
php artisan cache:clear

php-fpm8.2
