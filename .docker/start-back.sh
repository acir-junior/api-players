#!/bin/bash

set -e

cd /var/www/html

echo "Iniciando composer install"
composer install || { echo 'Composer install falhou'; exit 1; }

# Verificar se a pasta vendor foi criada
if [ ! -d "vendor" ]; then
    echo "Pasta vendor não encontrada, abortando..."
    exit 1
fi

echo "Ajustando permissões do artisan"
chmod +x /var/www/html/artisan

if [ ! -f ".env" ]; then
    echo "Arquivo .env não encontrado, copiando .env.example para .env"
    cp .env.example .env
fi

echo "Executando migrations"
php artisan migrate || { echo 'Migrations falharam'; exit 1; }

echo "Cacheando rotas"
php artisan route:cache || { echo 'Falha ao cachear rotas'; exit 1; }

echo "Cacheando configurações"
php artisan config:cache || { echo 'Falha ao cachear config'; exit 1; }

echo "Limpando caches"
php artisan optimize:clear || { echo 'Falha ao limpar caches'; exit 1; }
php artisan cache:clear || { echo 'Falha ao limpar caches de aplicação'; exit 1; }

echo "Iniciando PHP-FPM"
php-fpm
