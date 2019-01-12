#!/bin/sh
set -e

chown -R nobody:nobody /var/www/mawaqit/var/cache
chown -R nobody:nobody /var/www/mawaqit/var/logs
chown -R nobody:nobody /var/www/mawaqit/var/sessions

php-fpm7 -D
nginx -g 'daemon off;'