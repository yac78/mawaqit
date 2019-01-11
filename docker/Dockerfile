FROM alpine:latest

RUN apk update \
    apk upgrade

RUN apk --no-cache add php7 php7-fpm php7-mbstring php7-pdo_mysql php7-session php7-json php7-curl php7-tokenizer \
    php7-xml php7-ctype php7-simplexml php7-iconv php7-zip php7-dom php7-opcache php7-imagick \
    acl nginx supervisor curl imagemagick wget vim git gzip

COPY config/nginx.conf /etc/nginx/nginx.conf
COPY config/fpm-pool.conf /etc/php7/php-fpm.d/mawaqit_custom.conf
COPY config/php.ini /etc/php7/conf.d/mawaqit_custom.ini

COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

VOLUME /var/www
WORKDIR /var/www

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]