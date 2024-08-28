FROM node:18 as build

WORKDIR /app-build

COPY package*.json ./

RUN npm install

COPY resources/ ./resources/
COPY vite.config.js ./

RUN npm run build

FROM php:8.3-fpm

RUN rm -rf vendor && rm -rf composer.lock

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    libonig-dev \
    unzip \
    git \
    curl \
    zip \
    && docker-php-ext-install curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) mbstring \
    && docker-php-ext-install -j$(nproc) pdo \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) pdo_pgsql \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) bcmath \
    && docker-php-ext-install -j$(nproc) soap \
    && docker-php-ext-install -j$(nproc) pcntl \
    && docker-php-ext-install -j$(nproc) opcache

RUN apt-get install -y libtool libssl-dev pkg-config \
    && pecl install redis \
    && docker-php-ext-enable redis

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo zip exif pcntl pgsql

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY artisan /var/www/html
COPY composer.* /var/www/html
COPY app/ /var/www/html/app
COPY bootstrap/ /var/www/html/bootstrap
COPY config/ /var/www/html/config
COPY database/ /var/www/html/database
COPY public/ /var/www/html/public
COPY resources/ /var/www/html/resources
COPY routes/ /var/www/html/routes
COPY storage/ /var/www/html/storage
COPY .docker/ /var/www/html/.docker
COPY nginx/ /var/www/html/nginx
COPY .env.example /var/www/html
COPY .env.prod /var/www/html

COPY --from=build /app-build/public/build /var/www/html/public/build

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html

RUN chmod +x /var/www/html/.docker/start-back.sh

CMD ["/bin/bash", "/var/www/html/.docker/start-back.sh"]
