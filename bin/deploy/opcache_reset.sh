#!/bin/bash
WEBDIR=/var/www/mawaqit/prod/current/web/
RANDOM_NAME=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 13)
echo "<?php opcache_reset();" > ${WEBDIR}${RANDOM_NAME}.php
curl -ks https://localhost/${RANDOM_NAME}.php > /dev/null
rm ${WEBDIR}${RANDOM_NAME}.php