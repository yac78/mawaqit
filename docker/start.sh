#!/bin/sh

php-fpm7 -F & nginx -g 'daemon off;'

#while :; do sleep 6h & wait $${!}; nginx -s reload; done & nginx -g 'daemon off;'