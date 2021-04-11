FROM php:7.4-fpm-alpine

RUN apk add --no-cache \
    coreutils \
    php7-fpm \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-gd \
    php7-iconv \
    php7-json \
    php7-intl \
    php7-fileinfo\
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-mongodb \
    php7-xml \
    php7-zlib \
    php7-phar \
    php7-tokenizer \
    php7-session \
    php7-simplexml \
    php7-zip \
    php7-xmlwriter \
    php7-pecl-apcu \
    make \
    curl \
    bash

# Allow to enable php7-xdebug
ARG ENABLE_PHP_XDEBUG
RUN if [[ "$ENABLE_PHP_XDEBUG" == "1" ]]; then \
        apk add --no-cache php7-pecl-xdebug; \
    fi

RUN curl -sS https://getcomposer.org/installer | tee composer-setup.php \
    && php7 composer-setup.php && rm composer-setup.php* \
    && chmod +x composer.phar && mv composer.phar /usr/bin/composer

COPY application.ini /etc/php7/conf.d/
COPY application.ini /etc/php7/cli/conf.d/
COPY xdebug.ini  /etc/php7/conf.d/

COPY application.pool.conf /etc/php7/php-fpm.d/

WORKDIR /var/www/application

CMD ["php-fpm7", "-F"]

EXPOSE 9001
