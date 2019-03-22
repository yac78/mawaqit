FROM debian:latest

MAINTAINER Ibrahim Zehhaf <ibrahim.zehhaf.pro@gmail.com>

RUN apt-get update && \
    apt-get install -y \
    apt-transport-https \
    ca-certificates \
    curl \
    wget \
    vim \
    git \
    gzip \
    nginx \
    zip \
    imagemagick

# install php
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg && \
    sh -c 'echo "deb https://packages.sury.org/php/ stretch main" > /etc/apt/sources.list.d/php.list'

RUN apt-get update && \
    apt-get install -y \
    php7.1 \
    php7.1-fpm \
    php7.1-mysql \
    php7.1-curl \
    php7.1-json \
    php7.1-xml \
    php7.1-zip \
    php7.1-opcache \
    php7.1-imagick


# install composer
RUN curl -k -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#RUN chmod +x /usr/local/bin/composer

RUN mkdir /var/www/mawaqit
WORKDIR /var/www/mawaqit

ENTRYPOINT nginx && service php7.1-fpm start && /bin/bash