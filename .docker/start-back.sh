#!/bin/bash

set -e

composer install

chmod +x /var/www/html/artisan

if [ ! -f ".env" ]; then
    cp .env.example .env
fi

if [ "$APP_ENV" == "production" ]; then
    echo "Executando as migrations em produção"
    php artisan migrate --force --env=production
else
    echo "Executando as migrations local"
    php artisan migrate
fi

php artisan route:cache &&
php artisan config:cache &&
php artisan optimize:clear &&
php artisan cache:clear

php-fpm
