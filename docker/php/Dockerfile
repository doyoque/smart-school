FROM php:8.0-fpm-alpine

LABEL maintainer="Abiyoga Bayu Primadi"

#--------------------------------------------------
# Timezone
#--------------------------------------------------

ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

#--------------------------------------------------
# APP_ROOT, APP_USER
#--------------------------------------------------

ENV APP_ROOT=/var/www/html \
    APP_USER=www-data

RUN sed -ri 's/^www-data:x:82:82:/www-data:x:1000:50:/' /etc/passwd

RUN set -ex \
    && mkdir -p "${APP_ROOT}" \
    && chown -Rf "${APP_USER}:${APP_USER}" "${APP_ROOT}"

#--------------------------------------------------
# Composer
#--------------------------------------------------

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN set -ex \
    && php -r "copy('https://raw.githubusercontent.com/composer/getcomposer.org/master/web/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer \
    && mkdir -p /home/www-data/.composer/cache \
    && chown -R ${APP_USER}:${APP_USER} /home/www-data/.composer

#--------------------------------------------------
# PHP
#--------------------------------------------------
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=1 \
    PHP_OPCACHE_SAVE_COMMENTS=1

RUN set -ex \
    && apk add --no-cache --virtual .php-deps \
    libzip-dev \
    icu-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    git \
    postgresql-dev \
    && docker-php-ext-configure gd \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) \
    intl \
    opcache \
    pdo_mysql \
    pdo \
    gd \
    zip \
    xml

EXPOSE 9003

COPY ./conf.d/*.ini /usr/local/etc/php/conf.d/
