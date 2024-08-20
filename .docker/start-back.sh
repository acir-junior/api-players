#!/bin/bash

set -e

composer install

chmod +x /var/www/html/artisan

if [ ! -f ".env" ] && [ "$APP_ENV" == "production" ]; then
    cp .env.prod .env
else 
    cp .env.example .env
fi

if [ "$APP_ENV" == "production" ]; then
    echo "Executando as migrations em produção"
    php artisan migrate --force
else
    echo "Executando as migrations local"
    php artisan migrate --force
fi

php artisan route:cache &&
php artisan config:cache &&
php artisan optimize:clear &&
php artisan cache:clear

php-fpm
