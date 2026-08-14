FROM php:8.3-fpm-bullseye

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip

# Extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd xml

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Expõe a porta para o Nginx
EXPOSE 9000

CMD ["php-fpm"]
