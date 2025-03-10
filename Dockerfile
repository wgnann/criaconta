FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    git \
    zip \
    unzip

# cleanup
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# php libs
RUN docker-php-ext-install \
    zip

# apache
RUN a2enmod rewrite
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# composer cache
COPY composer.json composer.lock ./
USER www-data
RUN composer install --no-interaction --no-dev --no-autoloader

# criaconta
USER root
COPY --chown=www-data . .
USER www-data
RUN composer dump-autoload

CMD ["./serve.sh"]

# fonte:
# [1] https://www.digitalocean.com/community/tutorials/how-to-install-and-set-up-laravel-with-docker-compose-on-ubuntu-22-04
# [2] https://github.com/docker-library/php
