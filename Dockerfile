FROM alpine:latest

MAINTAINER Ibrahim Zehhaf <ibrahim.zehhaf.pro@gmail.com>

RUN apk --update add php7 php7-fpm php7-mbstring php7-pdo_mysql php7-session php7-json php7-curl php7-tokenizer \
    php7-xml php7-ctype php7-simplexml php7-iconv php7-zip php7-dom php7-opcache php7-imagick php7-fileinfo \
    acl nginx curl imagemagick wget vim git gzip \
    && rm -rf /var/cache/apk/*

COPY docker/config/nginx.conf /etc/nginx/nginx.conf
COPY docker/config/fpm-pool.conf /etc/php7/php-fpm.d/mawaqit_custom.conf
COPY docker/config/php.ini /etc/php7/conf.d/mawaqit_custom.ini

RUN mkdir /var/www/mawaqit
WORKDIR /var/www/mawaqit

COPY ["./docker/start.sh", "/tmp/start.sh"]
CMD ["/tmp/start.sh"]