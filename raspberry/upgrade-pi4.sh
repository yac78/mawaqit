#!/bin/bash

cd /home/pi/mawaqit

wget -q --spider http://google.com

if [ $? -ne 0 ]; then
    echo "offline";
    exit 1;
fi

git fetch

currenttag=$(git describe --tags --abbrev=0)
latesttag=$(git tag | sort -V | tail -1)

if [ "$currenttag" != "$latesttag" ]; then
    echo checking out ${latesttag}
    git checkout ${latesttag}
    version=`echo $latesttag | sed 's/-.*//'`
    sed -i "s/version: .*/version: $version/" app/config/parameters.yml
    docker-compose run mawaqit_composer sh -c "export SYMFONY_ENV=raspberry; composer install -o -n --no-dev"
    docker-compose exec mawaqit_php bin/console assets:install --env=raspberry --no-debug
    docker-compose exec mawaqit_php bin/console assetic:dump --env=raspberry --no-debug
    docker-compose exec mawaqit_php sh -c "export SYMFONY_ENV=raspberry; bin/console doc:mig:mig -n --allow-no-migration"
    docker-compose exec mawaqit_php rm -rf var/cache/* var/logs/* var/sessions/*
    docker-compose exec mawaqit_php sh -c "bin/console c:c --no-warmup -e raspberry && bin/console c:w -e raspberry"
    docker-compose exec mawaqit_php chmod  777 -R var/cache var/logs var/sessions
    
else
    echo "You are on the last version $latesttag :)"
fi
