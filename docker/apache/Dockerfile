# docker/apache/Dockerfile
FROM php:8.3-apache

ARG INSTALL_COMPOSER=true
ENV DEBIAN_FRONTEND=noninteractive

# System deps + php build deps + ssl + sasl + pkg-config
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
      git unzip zip libzip-dev libonig-dev libxml2-dev zlib1g-dev curl ca-certificates build-essential \
      pkg-config libssl-dev libsasl2-dev cmake \
 && docker-php-ext-install bcmath pcntl sockets zip \
 && pecl channel-update pecl.php.net \
 && pecl install mongodb redis \
 && docker-php-ext-enable mongodb redis \
 && a2enmod rewrite \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/* /tmp/pear

# composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/laravel

# copy app into image (helps seeding vendor on build)
COPY ./laravel /var/www/laravel

# install composer deps at build-time (optional)
RUN if [ "${INSTALL_COMPOSER}" = "true" ]; then \
      cd /var/www/laravel && \
      composer install --no-dev --prefer-dist --no-progress --no-interaction || true; \
    fi

# write virtual host pointing to public and allow .htaccess
RUN cat > /etc/apache2/sites-available/000-default.conf <<'APACHECONF'
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/laravel/public

    <Directory /var/www/laravel/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    DirectoryIndex index.php index.html
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
APACHECONF

# ensure ownership
RUN chown -R www-data:www-data /var/www/laravel/storage || true
RUN chown -R www-data:www-data /var/www/laravel/bootstrap/cache || true

EXPOSE 80
CMD ["apache2-foreground"]
