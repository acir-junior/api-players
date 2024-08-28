#!/bin/bash

set -e

chmod +x /var/www/html/artisan

if [ "$ENV" = "production" ]; then
    echo "Instalando dependências em produção"

    cp .env.prod .env
    composer install --no-dev --prefer-dist --no-progress --no-suggest

    php artisan migrate --force
else
    cp .env.example .env
    composer install

    php artisan migrate
fi

php artisan route:cache &&
php artisan config:cache &&
php artisan optimize:clear &&
php artisan cache:clear

php-fpm
