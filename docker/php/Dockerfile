FROM php:8.4-fpm AS php_base

COPY ./docker/php/conf.d /usr/local/etc/php/conf.d

## < Install PHP extensions
ENV XDEBUG_MODE=off

RUN docker-php-ext-install opcache

RUN pecl install redis \
	&& docker-php-ext-enable redis

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libmemcached-dev=1.1.4-1 \
        libssl-dev=3.0.16-1~deb12u1 \
        zlib1g-dev=1:1.2.13.dfsg-1 \
	&& pecl install memcached-3.2.0 \
	&& docker-php-ext-enable memcached \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install apcu \
    && docker-php-ext-enable apcu

RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev=15.13-0+deb12u1 \
    && docker-php-ext-install pdo_pgsql pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libzip-dev=1.7.3-1+b1 \ 
        zlib1g-dev=1:1.2.13.dfsg-1 \
    && docker-php-ext-install zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libicu-dev=72.1-3 \
    && docker-php-ext-install intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install ctype iconv
## > Install PHP extensions

## < Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
## > Install composer

FROM php_base AS php_dev

ENV APP_ENV=dev

RUN pecl install xdebug \
	&& docker-php-ext-enable xdebug

## < Install chromedriver
ENV PANTHER_NO_SANDBOX=1
ENV PANTHER_NO_REDUCED_MOTION=1
ENV PANTHER_ERROR_SCREENSHOT_DIR=/var/www/html/var/screenshots
ENV PANTHER_DEVTOOLS=disabled
ENV PANTHER_CHROME_ARGUMENTS='--disable-dev-shm-usage'
ENV GNUPGHOME=/usr/local/share/gnupg

RUN apt-get update && apt-get install -y --no-install-recommends \
        gpg=2.2.40-1.1 \
        unzip=6.0-28 \
        wget=1.21.3-1+deb12u1 \
        chromium=137.0.7151.55-3~deb12u1 \ 
        chromium-driver=137.0.7151.55-3~deb12u1 \ 
        libgconf-2-4=3.2.6-8 \
        libfontconfig-dev=2.14.1-4 \
        libfreetype6=2.12.1+dfsg-5+deb12u4 \
        libx11-6=2:1.8.4-2+deb12u2 \
        libxext6=2:1.3.4-1+b1 \
        libxrender1=1:0.9.10-1.1 \
        libxtst6=2:1.2.3-1.1 \
        dirmngr=2.2.40-1.1 \
        gpg-agent=2.2.40-1.1 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p $GNUPGHOME \
    && chmod 700 $GNUPGHOME \
    && wget -q -nv -O phive.phar https://phar.io/releases/phive.phar \
    && wget -q -nv -O phive.phar.asc https://phar.io/releases/phive.phar.asc \    
    && gpg --refresh-keys \
    && gpg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 0x9D8A98B29B2D5D79 \
    && gpg --verify phive.phar.asc phive.phar \
    && chmod +x phive.phar \
    && mv phive.phar /usr/local/bin/phive

ENV DEBIAN_FRONTEND=noninteractive

SHELL ["/bin/bash", "-o", "pipefail", "-c"]
RUN (echo "y" | /usr/local/bin/phive install --global bdi) \
    && bdi detect /usr/local/bin/
## > Install chromedriver

FROM php_base AS php_prod

## < Install nginx
RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx=1.22.1-9+deb12u2 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/app.conf /etc/nginx/conf.d/app.conf
RUN rm /etc/nginx/sites-enabled/default
## > Install nginx

WORKDIR /var/www/html

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

## < Cleanup
RUN rm index.nginx-debian.html .env \
    && touch .env
## > Cleanup

## < Setup Supervisor
USER root

RUN apt-get update && apt-get install -y --no-install-recommends \
        supervisor=4.2.5-1 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN useradd -r -s /bin/false supervisoruser

RUN mkdir -p /var/log/supervisor && \
chmod -R 755 /var/log/supervisor && \
chown -R supervisoruser:supervisoruser /var/log/supervisor

COPY ./docker/web/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
## > Setup Supervisor
