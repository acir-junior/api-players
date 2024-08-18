FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl 

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN rm -rf vendor && rm -rf composer.lock

COPY . /var/www/html

WORKDIR /var/www/html

RUN chmod +x /var/www/html/.docker/start-back.sh

CMD [ "/var/www/html/.docker/start-back.sh" ]

EXPOSE 8080