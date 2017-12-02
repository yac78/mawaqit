#!/bin/bash

WEBDIR=/var/www/mawaqit/$1/current/web/
FILE_NAME="opcacheReset.php"
echo "<?php opcache_reset();" > ${WEBDIR}${FILE_NAME}
curl -ks https://localhost/${FILE_NAME} > /dev/null
rm ${WEBDIR}${FILE_NAME}