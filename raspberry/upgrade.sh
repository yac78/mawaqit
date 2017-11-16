#!/bin/bash

cd /home/pi/prayer-times-v3

git fetch

latesttag=$(git describe --tags $(git rev-list --tags --max-count=1))
echo checking out ${latesttag}
git checkout ${latesttag}

composer install --optimize-autoloader --no-interaction

version=`echo $latesttag | sed 's/-.*//'`

sed -i "s/version: .*/version: $version/" app/config/parameters.yml

bin/console c:c -e prod

bin/console doctrine:migrations:migrate -n --allow-no-migration
    
echo "Upgrade  has been successfully done ;)"
