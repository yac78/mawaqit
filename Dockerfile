FROM alpine:latest

MAINTAINER Ibrahim Zehhaf <ibrahim.zehhaf.pro@gmail.com>

RUN apk --no-cache add php7 php7-fpm php7-mbstring php7-pdo_mysql php7-session php7-json php7-curl php7-tokenizer \
    php7-xml php7-ctype php7-simplexml php7-iconv php7-zip php7-dom php7-opcache php7-imagick php7-fileinfo \
    nginx gzip curl imagemagick wget vim git

RUN mkdir -p /var/www/mawaqit
WORKDIR /var/www/mawaqit