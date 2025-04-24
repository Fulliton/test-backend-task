FROM php:8.4-fpm-alpine

WORKDIR /var/www

ENV COMPOSER_HOME="/tmp/composer"
COPY --from=composer:2.6.6 /usr/bin/composer /usr/bin/composer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions pdo_pgsql

COPY conf.d/local.ini /usr/local/etc/php/conf.d/local.ini

USER appuser:appuser

CMD ["php-fpm"]
