FROM php:8.4-fpm AS php_base

COPY ./docker/php/php.ini-development /usr/local/etc/php/php.ini

## < Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
## > Install composer

## < Install PHP extensions
ENV XDEBUG_MODE=off

RUN docker-php-ext-install opcache

RUN pecl install redis \
	&& docker-php-ext-enable redis

RUN apt-get update \
    && apt-get install -y \
        libmemcached-dev \
        libssl-dev \
        zlib1g-dev \
	&& pecl install memcached-3.2.0 \
	&& docker-php-ext-enable memcached

RUN pecl install apcu \
    && docker-php-ext-enable apcu

RUN apt-get update && apt-get install -y \
        libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql

RUN apt-get update \
    && apt-get install -y libzip-dev zlib1g-dev \
    && docker-php-ext-install zip

RUN apt-get update \
    && apt-get install -y libicu-dev \
    && docker-php-ext-install intl

RUN docker-php-ext-install ctype iconv

RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*
## > Install PHP extensions

FROM php_base AS php_dev

ENV APP_ENV=dev

RUN pecl install xdebug \
	&& docker-php-ext-enable xdebug

## < Install chromedriver
ENV PANTHER_NO_SANDBOX=1
ENV PANTHER_CHROME_ARGUMENTS='--disable-dev-shm-usage'
ENV GNUPGHOME=/usr/local/share/gnupg

RUN apt-get update && apt-get install -y \
    gpg \
    unzip \
    wget \
    chromium \ 
    libgconf-2-4 \
    libfontconfig \
    libfreetype6 \
    libx11-6 \
    libxext6 \
    libxrender1 \
    libxtst6

RUN mkdir -p $GNUPGHOME \
    && chmod 700 $GNUPGHOME \
    && wget -O phive.phar https://phar.io/releases/phive.phar \
    && wget -O phive.phar.asc https://phar.io/releases/phive.phar.asc \    
    && gpg --refresh-keys \
    && gpg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 0x9D8A98B29B2D5D79 \
    && gpg --verify phive.phar.asc phive.phar \
    && chmod +x phive.phar \
    && mv phive.phar /usr/local/bin/phive

ENV DEBIAN_FRONTEND=noninteractive

RUN (echo "y" | /usr/local/bin/phive install --global bdi) \
    && bdi detect /usr/local/bin/

RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/*
## > Install chromedriver

FROM php_base AS php_prod

COPY ./docker/php/php.ini-production /usr/local/etc/php/php.ini

## < Install nginx
RUN apt-get update && apt-get install -y \
nginx \
&& apt-get clean \
&& rm -rf /var/lib/apt/lists/*

COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/app.conf /etc/nginx/conf.d/app.conf
RUN rm /etc/nginx/sites-enabled/default
## > Install nginx

RUN set -eux;

EXPOSE 8080

USER www-data

ENV APP_ENV=prod
ENV XDEBUG_MODE=off

## < Composer stuff
COPY --chown=www-data composer.* symfony.* ./
RUN set -eux; \
	composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress

COPY --chown=www-data . ./
RUN mv .env.prod .env && chown www-data:www-data .env

RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer run-script --no-dev post-install-cmd; \
	sync;
## > Composer stuff

## < Setup Supervisor
USER root

RUN apt-get update && apt-get install -y \ 
    supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN useradd -r -s /bin/false supervisoruser
RUN mkdir -p /var/log/supervisor && \
    chmod -R 755 /var/log/supervisor && \
    chown -R supervisoruser:supervisoruser /var/log/supervisor

COPY ./docker/web/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# USER supervisoruser
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
## > Setup Supervisor

## < Cleanup
RUN rm index.nginx-debian.html .env \
    && touch .env
## > Cleanup