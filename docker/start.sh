#!/bin/sh
set -e

php-fpm7 -D
nginx -g 'daemon off;'

chown -R www-data:www-data /var/www/var/cache
chown -R www-data:www-data /var/www/var/logs
chown -R www-data:www-data /var/www/var/sessions