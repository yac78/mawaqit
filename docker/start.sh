#!/bin/sh
set -e

php-fpm7 -D
nginx -g 'daemon off;'