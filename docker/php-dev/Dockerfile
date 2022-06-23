ARG PHP_BASEIMAGE_VERSION=8.1
FROM php:${PHP_BASEIMAGE_VERSION}-fpm-alpine

ARG PHP_EXTENSIONS="dom exif gd intl opcache pdo pdo_pgsql soap xsl zip ldap"

RUN apk add --no-cache --update \
        libzip-dev curl-dev libxml2-dev icu-dev oniguruma-dev autoconf git openssh-client openssh-keygen \
    && \
    apk add --update --no-cache --virtual .build-deps \
		acl \
		apk-cron \
		augeas-dev \
		autoconf \
		bash \
		curl \
		ca-certificates \
		dialog \
		freetype-dev \
		gomplate \
		git \
		gcc \
		icu-dev \
		libcurl \
		libffi-dev \
		libgcrypt-dev \
		libjpeg-turbo-dev \
		libmcrypt-dev \
		libpng-dev \
		libpq \
		libressl-dev \
		libxslt-dev \
		libzip-dev \
		imap-dev \
		linux-headers \
		make \
		musl-dev \
        postgresql-dev \
		supervisor \
		su-exec \
		wget \
		gnu-libiconv \
        ldb-dev libldap openldap-dev \
       alpine-sdk && \
   docker-php-ext-configure imap --with-imap --with-imap-ssl && \
    docker-php-ext-install $PHP_EXTENSIONS && \
    apk del .build-deps

RUN apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted gnu-libiconv
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

# Reset all configs
RUN rm $PHP_INI_DIR/conf.d/*

RUN sed -i \
		-e "s/;catch_workers_output\s*=\s*yes/catch_workers_output = yes/g" \
		-e "s/^;clear_env = no$/clear_env = no/" \
		/usr/local/etc/php-fpm.d/www.conf

# Add PHP config:
COPY conf.d/ $PHP_INI_DIR/conf.d/

WORKDIR /var/www

# Composer section
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /root/composer/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN install-php-extensions gd ldap soap intl zip pdo_pgsql xsl

#RUN pecl install xdebug \
#    && docker-php-ext-enable xdebug


RUN chown www-data:www-data /var/www

USER www-data

USER root

CMD ["php-fpm"]
EXPOSE 9000
