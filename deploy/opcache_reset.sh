#!/bin/bash

WEBDIR=/var/www/mawaqit/prod/current/web/
FILE_NAME="opcacheReset.php"
echo "<?php opcache_reset();" > ${WEBDIR}${FILE_NAME}
curl -sk https://mawaqit.net/${FILE_NAME} > /dev/null
rm ${WEBDIR}${FILE_NAME}