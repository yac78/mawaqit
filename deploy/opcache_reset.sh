#!/bin/bash

WEBDIR=/var/www/mawaqit/$1/current/web/
RANDOM_NAME=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 13)
echo "<?php opcache_reset(); ?>" > ${WEBDIR}${RANDOM_NAME}.php
curl -sk https://localhost/${RANDOM_NAME}.php
rm ${WEBDIR}${RANDOM_NAME}.php