#!/bin/bash

set -e

if [ ! -f ".env" ] && [ "$APP_ENV" == "production" ]; then
    cp .env.prod .env
else 
    cp .env.example .env
fi


if [ "$APP_ENV" == "production" ]; then
    echo "Instalando dependências em produção"
    composer install --no-dev --prefer-dist --no-progress --no-suggest
else
    composer install
fi

chmod +x /var/www/html/artisan

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
