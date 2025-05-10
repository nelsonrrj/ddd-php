FROM php:8.3.6-cli

# Install db complements. Postgre complements are comment. 
RUN apt-get update && apt-get install -y \
  zip \
  unzip \
  git \
  && docker-php-ext-install pdo pdo_mysql

# Composer install
COPY --from=composer:2.7.4 /usr/bin/composer /usr/local/bin/composer

# Xdebug complements
RUN pecl install -o -f xdebug
COPY ./xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN docker-php-ext-enable xdebug

# Basic
WORKDIR /var/www/html
COPY . .

EXPOSE 9003

CMD ["php", "-a"]