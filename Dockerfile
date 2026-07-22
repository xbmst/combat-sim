ARG PHP_VERSION=8.5

# Runtime base
FROM dunglas/frankenphp:1-php${PHP_VERSION}-alpine AS php_base

WORKDIR /app

RUN install-php-extensions \
    pdo_pgsql \
    redis

# Composer bootstrap
FROM php_base AS composer_base

RUN install-php-extensions zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN mkdir -p /app /tmp/composer \
    && chown -R www-data:www-data /app /tmp/composer

USER www-data

COPY --chown=www-data:www-data composer.json composer.lock symfony.lock* ./

# build stage
FROM composer_base AS builder

COPY --chown=www-data:www-data . .

RUN rm -rf vendor \
    && composer install \
        --no-dev \
        --no-scripts \
        --prefer-dist \
        --optimize-autoloader \
        --classmap-authoritative \
        --no-interaction

# API service
FROM php_base AS api

COPY --from=builder --chown=www-data:www-data /app /app

COPY docker/frankenphp/Caddyfile /etc/caddy/Caddyfile
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/zz-opcache.ini

RUN mkdir -p \
        /app/var/cache \
        /app/var/log \
        /config/caddy \
        /data/caddy \
        /data/caddy/locks \
    && chown -R www-data:www-data \
        /app/var \
        /config \
        /data

USER www-data

EXPOSE 80

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]

# Messenger worker
FROM php_base AS worker

RUN install-php-extensions pcntl

COPY --from=builder --chown=www-data:www-data /app /app

RUN mkdir -p /app/var/cache /app/var/log \
    && chown -R www-data:www-data /app/var

USER www-data

CMD ["sh", "-lc", "exec php bin/console --no-debug messenger:consume async --memory-limit=128M --time-limit=3600 -vv"]
