# syntax=docker/dockerfile:1

##############################################################################
# Stage 1 — Composer (PHP) dependencies
##############################################################################
FROM composer:2 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./

# Install production dependencies only. --no-scripts because artisan/the full
# app isn't present in this stage yet; package discovery runs at container start.
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
        --ignore-platform-reqs

##############################################################################
# Stage 2 — Frontend assets (Vite build)
##############################################################################
FROM node:20-alpine AS assets

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

##############################################################################
# Stage 3 — Runtime image (php-fpm + nginx + supervisor)
##############################################################################
FROM php:8.1-fpm-alpine AS app

# --- System packages + PHP extensions ---------------------------------------
RUN set -eux; \
    apk add --no-cache \
        nginx \
        supervisor \
        bash \
        mariadb-client \
        libpng libjpeg-turbo libwebp freetype \
        icu-libs libzip oniguruma; \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev \
        icu-dev libzip-dev oniguruma-dev; \
    docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype; \
    docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        bcmath \
        gd \
        zip \
        intl \
        exif \
        pcntl \
        opcache; \
    apk del .build-deps

WORKDIR /var/www/html

# --- Application source (build context; .dockerignore trims junk) ------------
COPY . .

# --- Vendored deps + compiled assets from earlier stages --------------------
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

# --- Runtime directories & permissions --------------------------------------
RUN set -eux; \
    mkdir -p \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/cache/data \
        storage/logs \
        bootstrap/cache; \
    chown -R www-data:www-data storage bootstrap/cache; \
    chmod -R 775 storage bootstrap/cache

# --- Container configuration ------------------------------------------------
COPY docker/nginx.conf        /etc/nginx/nginx.conf
COPY docker/php.ini           /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/supervisord.conf  /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh     /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
