#!/bin/bash
set -e
if [ -f /var/www/app/console ]; then
    echo "Initialize Symfony 3"

    cd /var/www/
    setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx var/cache var/logs web/*/ || true
    setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx var/cache var/logs web/*/ || true
fi

#/usr/bin/supervisord -c /etc/supervisor/supervisord.conf