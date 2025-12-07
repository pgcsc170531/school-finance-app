# We use the official PHP image as our base (like the OS)
FROM php:8.3-fpm

# 1. Install standard Linux tools and libraries needed for Symfony & Postgres
RUN apt-get update && apt-get install -y \
    libpq-dev \
    librabbitmq-dev \
    libicu-dev \
    git \
    unzip \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && docker-php-ext-install pdo pdo_pgsql intl

# 2. Install Composer (the PHP package manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Set the folder inside the container where our code will live
WORKDIR /var/www/html