#!/bin/bash

cd /home/pi/mawaqit

git fetch

currenttag=$(git describe --tags --abbrev=0)
latesttag=$(git describe --tags $(git rev-list --tags --max-count=1))

if [ "$currenttag" != "$latesttag"]; then
    # stop chromium
    killall chromium-browser

    echo checking out ${latesttag}
    git checkout ${latesttag}

    composer install --optimize-autoloader --no-interaction
    bin/console assets:install --env=prod --no-debug
    bin/console assetic:dump --env=prod --no-debug

    version=`echo $latesttag | sed 's/-.*//'`

    sed -i "s/version: .*/version: $version/" app/config/parameters.yml

    bin/console c:c -e prod

    bin/console doctrine:migrations:migrate -n --allow-no-migration

    echo "The upgrade to v$version has been successfully done ;)"

    # run app
    raspberry/run.sh

else
    echo "You are on the laste version"
fi

