FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    nginx \
    curl \
    zip \
    unzip \
    git \
    sqlite

# Install ekstensi PHP dengan aman tanpa OOM / error kompilasi C++ di Railway
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
    pdo_mysql \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN cp docker/nginx.conf /etc/nginx/http.d/default.conf 2>/dev/null || cp docker/nginx.conf /etc/nginx/conf.d/default.conf

RUN chmod +x docker/entrypoint.sh \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/bin/sh", "docker/entrypoint.sh"]
