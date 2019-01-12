#!/bin/sh
set -e

chown -R nobody:nobody /var/www/var/cache
chown -R nobody:nobody /var/www/var/logs
chown -R nobody:nobody /var/www/var/sessions

php-fpm7 -D
nginx -g 'daemon off;'