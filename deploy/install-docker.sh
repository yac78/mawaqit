#!/bin/bash
set -e

env=$1
tag=$2
baseDir=/var/www/mawaqit
repoDir=$baseDir/repo
dockerContainer=mawaqit

cd $repoDir

docker exec $dockerContainer git fetch && git checkout $tag

echo "Creating symlinks"
docker exec $dockerContainer sh -c "(cd web && ln -snf robots.txt.$env robots.txt)"

echo "Set version"
docker exec $dockerContainer sed -i "s/version: .*/version: $tag/" app/config/parameters.yml

# install vendors and assets
docker exec $dockerContainer sh -c "SYMFONY_ENV=prod composer install -on --no-dev"
docker exec $dockerContainer bin/console assets:install --env=prod --no-debug
docker exec $dockerContainer bin/console assetic:dump --env=prod --no-debug

# backup DB if prod deploy
if [ "$env" == "prod" ]; then
    echo "Backup prod DB"
    $baseDir/tools/dbSync.sh
fi

# migrate DB
docker exec $dockerContainer bin/console doc:mig:mig -n --allow-no-migration -e prod

echo "Reset opcache"
#docker exec $dockerContainer curl -X DELETE -H "Api-Access-Token: f18e7703-01a3-4869-b3cc-7153af421911" http://localhost/api/1.0.0/tools/opcache/reset

echo ""
echo "####################################################"
echo "The upgrade to v$tag has been successfully done ;)"
echo "####################################################"