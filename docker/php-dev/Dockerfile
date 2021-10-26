FROM php:7.4.9-fpm-alpine

RUN apk add --no-cache --update \
        libzip-dev curl-dev libxml2-dev icu-dev oniguruma-dev autoconf \
    && \
    apk add --update --no-cache --virtual .build-deps \
       g++ \
       gcc \
       gnupg \
       libgcc \
       make \
       alpine-sdk && \
    docker-php-ext-install \
        curl \
        xml \
        soap \
        phar \
        json \
        iconv \
        ctype \
        dom \
        mbstring \
        tokenizer \
        pdo \
        bcmath \
        simplexml \
        sockets \
        opcache \
        zip \
        pcntl \
        xmlwriter && \
    pecl install xdebug-2.9.0 && \
    docker-php-ext-enable xdebug && \
    apk del .build-deps

RUN apk add --no-cache --update git openssh-client openssh-keygen

RUN apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted gnu-libiconv
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

# Reset all configs
RUN rm $PHP_INI_DIR/conf.d/*

# Add PHP config:
COPY conf.d/ $PHP_INI_DIR/conf.d/

WORKDIR /var/www

# Composer section
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /root/composer/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN install-php-extensions gd ldap soap intl zip pdo_pgsql sockets xsl

RUN chown www-data:www-data /var/www

USER www-data

USER root

CMD ["php-fpm"]
EXPOSE 9000
