# docker/php/Dockerfile
FROM php:8.3-fpm-alpine

ARG INSTALL_COMPOSER=true

# Install build deps (virtual) and also install runtime packages (not removed)
RUN apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS autoconf build-base linux-headers \
        zlib-dev libzip-dev oniguruma-dev openssl-dev pkgconfig cmake cyrus-sasl-dev \
    && apk add --no-cache \
        bash curl git libzip openssl ca-certificates cyrus-sasl \
    \
    && docker-php-ext-install pcntl bcmath sockets zip \
    \
    && pecl channel-update pecl.php.net \
    && pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis \
    \
    # remove build-only deps but keep runtime packages (cyrus-sasl, openssl) above
    && apk del .build-deps \
    && rm -rf /var/cache/apk/* /tmp/pear

# composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/laravel

# copy app files (used for seeding vendor if you choose)
COPY ./laravel /var/www/laravel

# optionally install composer deps at build-time
RUN if [ "${INSTALL_COMPOSER}" = "true" ]; then \
      cd /var/www/laravel && \
      composer install --no-dev --prefer-dist --no-progress --no-interaction --no-scripts || true; \
    fi

# ensure storage & cache ownership
RUN chown -R www-data:www-data /var/www/laravel/storage || true
RUN chown -R www-data:www-data /var/www/laravel/bootstrap/cache || true

EXPOSE 9000
CMD ["php-fpm"]
