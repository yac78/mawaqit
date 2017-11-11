#!/bin/bash

cd /home/pi/prayer-times-v3

git fetch

latesttag=$(git describe --tags)
echo checking out ${latesttag}
git checkout ${latesttag}

composer install

version=`echo $latesttag | sed 's/-.*//'`

sed -i "s/version: .*/version: $version/" app/config/parameters.yml

bin/console c:c -e prod

bin/console doctrine:migrations:migrate -n
    
echo "Upgrade  has been successfully done ;)"
