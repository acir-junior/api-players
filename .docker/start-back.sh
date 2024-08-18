#!/bin/bash

set -e

COMPOSER_MEMORY_LIMIT=-1 composer install

chmod +x /var/www/html/artisan

if [ ! -f ".env" ]; then
    cp .env.example .env
fi

php artisan migrate

php artisan route:cache &&
php artisan config:cache &&
php artisan optimize:clear &&
php artisan cache:clear

php-fpm
