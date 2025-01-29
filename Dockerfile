FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    curl \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql intl zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

COPY . .
RUN composer dump-autoload

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

RUN apt-get install -y nginx
COPY ./nginx.conf /etc/nginx/sites-available/default

CMD service nginx start && php-fpm
EXPOSE 80
