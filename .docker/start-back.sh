#!/bin/bash

set -e

composer install

chmod +x /var/www/html/artisan

if [ ! -f ".env" ]; then
    cp .env.example .env
fi

php artisan migrate

php artisan route:cache &&
php artisan config:cache &&
php artisan optimize:clear &&
php artisan cache:clear

php artisan serve --host=0.0.0.0 --port=8080