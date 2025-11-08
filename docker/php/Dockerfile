# docker/php/Dockerfile
FROM php:8.3-fpm-alpine

ARG INSTALL_COMPOSER=true

RUN apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS autoconf build-base linux-headers \
        zlib-dev libzip-dev oniguruma-dev openssl-dev \
    && apk add --no-cache bash curl git libzip \
    && docker-php-ext-install pcntl bcmath sockets zip \
    && pecl channel-update pecl.php.net \
    && pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/* /tmp/pear

# composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/laravel

# copy application files into the image when building with context '.'
# If Laravel folder is not present in build context this step will still work (COPY will fail),
# but because we build with context '.' it will be available.
COPY ./laravel /var/www/laravel

# optionally install composer dependencies during build
RUN if [ "${INSTALL_COMPOSER}" = "true" ]; then \
      cd /var/www/laravel && \
      composer install --no-dev --prefer-dist --no-progress --no-interaction --no-scripts || true; \
    fi

# ensure storage & cache ownership
RUN chown -R www-data:www-data /var/www/laravel/storage || true

EXPOSE 9000
CMD ["php-fpm"]
