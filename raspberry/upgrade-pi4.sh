#!/bin/bash

cd /home/pi/mawaqit

wget -q --spider http://google.com

if [ $? -ne 0 ]; then
    echo "offline";
    exit 1;
fi

git fetch

currenttag=$(git describe --tags --abbrev=0)
latesttag=$(git describe --tags $(git rev-list --tags --max-count=1))

if [ "$currenttag" != "$latesttag" ]; then

    echo checking out ${latesttag}
    git checkout ${latesttag}

    version=`echo $latesttag | sed 's/-.*//'`
    sed -i "s/version: .*/version: $version/" app/config/parameters.yml
    sudo rm -rf /tmp/*
    sudo rm -rf var/cache/* var/logs/*
    docker-compose run mawaqit_composer sh -c "export SYMFONT_ENV=raspberry && composer install -o -n"
    sudo rm -rf var/cache/* var/logs/*
    docker-compose exec mawaqit_php bin/console assets:install --env=raspberry --no-debug
    docker-compose exec mawaqit_php bin/console assetic:dump --env=raspberry --no-debug
    docker-compose exec mawaqit_php bin/console doc:mig:mig -n --allow-no-migration
    sudo rm -rf var/cache/* var/logs/* 
else
    echo "You are on the last version :)"
fi
