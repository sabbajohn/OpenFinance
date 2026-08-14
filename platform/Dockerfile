FROM php:8.4-cli-alpine AS vendor
RUN apk add --no-cache git icu-libs libzip libpq \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev libzip-dev postgresql-dev \
    && docker-php-ext-install intl pdo_pgsql pcntl zip \
    && apk del .build-deps
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /workspace
COPY packages ./packages
COPY platform/composer.json platform/composer.lock ./platform/
WORKDIR /workspace/platform
RUN composer install --no-dev --no-interaction --no-progress --no-scripts

FROM vendor AS frontend
RUN apk add --no-cache nodejs npm
WORKDIR /workspace
COPY platform ./platform
WORKDIR /workspace/platform
RUN php artisan wayfinder:generate --with-form \
    && npm ci \
    && npm run build

FROM php:8.4-fpm-alpine AS app-runtime
RUN apk add --no-cache icu-libs libzip libpq curl fcgi \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev libzip-dev postgresql-dev linux-headers \
    && docker-php-ext-install intl pdo_pgsql pcntl zip opcache \
    && apk del .build-deps
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
COPY packages ./packages
COPY platform ./html
COPY --from=vendor /workspace/platform/vendor ./html/vendor
COPY --from=frontend /workspace/platform/public/build ./html/public/build
WORKDIR /var/www/html
RUN composer dump-autoload --classmap-authoritative --no-dev --no-interaction \
    && chown -R www-data:www-data storage bootstrap/cache
USER www-data
EXPOSE 9000
HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD SCRIPT_NAME=/up SCRIPT_FILENAME=/var/www/html/public/index.php REQUEST_METHOD=GET cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1
CMD ["php-fpm", "-F"]

FROM nginx:1.27-alpine AS web
COPY platform/docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY --from=frontend /workspace/platform/public /var/www/html/public
