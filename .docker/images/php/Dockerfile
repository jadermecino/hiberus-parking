FROM php:8.2.9-fpm-alpine

COPY --from=composer:2.6.4 /usr/bin/composer /usr/bin/composer

RUN apk add shadow \
  && usermod -u 1000 www-data \
  && groupmod -g 1000 www-data \
  && apk add --no-cache $PHPIZE_DEPS \
    bash \
    curl \
    freetype-dev \
    gettext-dev \
    git \
    icu-dev \
    libjpeg-turbo-dev \
    libjpeg-turbo \
    libpng-dev \
    libwebp-dev \
    libxml2-dev \
    libxpm-dev \
    libxslt-dev \
    libzip-dev \
    linux-headers \
    mariadb \
    mariadb-client \
    unzip \
    vim \
    wget \
  && apk add --no-cache --update --virtual .build-dependencies \
    autoconf \
    automake \
    g++ \
    gcc \
    make \
  && pecl install apcu \
  && pecl install redis \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-configure zip \
  && docker-php-ext-install -j$(nproc) \
    bcmath \
    calendar \
    exif \
    gd \
    gettext \
    intl \
    mysqli \
    opcache \
    pdo_mysql \
    soap \
    sockets \
    xsl \
    zip \
  && docker-php-ext-enable apcu opcache redis \
  && pecl clear-cache \
  && apk del .build-dependencies \
  && rm -rf \
    /tmp/* \
    /var/log/* \
    /var/cache/apk/* \
  && mkdir -p /opt/home

USER www-data

WORKDIR /var/www/html

CMD ["php-fpm"]
