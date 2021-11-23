FROM php:7.4-fpm

RUN apt-get update && \
    apt-get -y --no-install-recommends install \
        git \
        libaio1 \
        libmagickwand-dev \
        libmpdec-dev \
        libssl-dev \
        libzip-dev \
        software-properties-common \
        uchardet \
        wget \
        zbar-tools \
        zlib1g-dev && \
    pecl install imagick &&  \
    docker-php-ext-enable imagick && \
    useradd -ms /bin/bash php-fpm && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --quiet && \
    rm composer-setup.php && \
    mv composer.phar /usr/local/bin/composer && \
    rm -rf /var/lib/{apt,dpkg,cache,log}/

RUN docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ && \
    docker-php-ext-install \
        bcmath \
        gd \
        intl \
        opcache \
        pdo_mysql \
        soap \
        sockets \
        zip \
        xml

RUN pecl install decimal && docker-php-ext-enable decimal


ENV COMPOSER_ALLOW_SUPERUSER 1

EXPOSE 9000