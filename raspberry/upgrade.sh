#!/bin/bash

cd /home/pi/mawaqit

wget -q --spider http://google.com

if [ $? -ne 0 ]; then
    echo "offline"
fi

git fetch

currenttag=$(git describe --tags --abbrev=0)
latesttag=$(git describe --tags $(git rev-list --tags --max-count=1))

if [ "$currenttag" != "$latesttag"]; then
    # stop chromium
    killall chromium-browser

    echo checking out ${latesttag}
    git checkout ${latesttag}

    version=`echo $latesttag | sed 's/-.*//'`
    sed -i "s/version: .*/version: $version/" app/config/parameters.yml

    composer install --optimize-autoloader --no-interaction
    sudo rm -rf var/cache/* var/logs/*

    bin/console assets:install --env=prod --no-debug
    bin/console assetic:dump --env=prod --no-debug

    bin/console doctrine:migrations:migrate -n --allow-no-migration
    raspberry/run.sh
else
    echo "You are on the laste version"
fi
