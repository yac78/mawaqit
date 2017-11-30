#!/bin/bash
WEBDIR=/var/www/mawaqit/prod/current/web/
RANDOM_NAME=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 13)
echo "<?php var_dump(opcache_reset());" > ${WEBDIR}${RANDOM_NAME}.php
curl http://localhost:443/${RANDOM_NAME}.php
rm ${WEBDIR}${RANDOM_NAME}.php