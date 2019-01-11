#!/bin/bash
set -e

if [ $# -lt 1 ]; then
    echo "tag is mandatory"
    exit;
fi

tag=$1
baseDir=/var/www/mawaqit
repoDir=$baseDir/repo
dockerContainer=mawaqit_prod

cd $repoDir

docker exec $dockerContainer git fetch && git checkout $tag

#echo "Creating symlinks"
#ln -snf $sharedDir/upload/ $targetDir/web/upload
#ln -snf $sharedDir/static $targetDir/web/static
#ln -snf $sharedDir/parameters.$env.yml $targetDir/app/config/parameters.yml
#ln -snf $sharedDir/robots.txt.$env $targetDir/web/robots.txt

#if [ "$env" == "prod" ]; then
#    ln -snf $sharedDir/logs $targetDir/var/logs
#    ln -snf $sharedDir/sessions $targetDir/var/sessions
#fi


echo "Set version"
docker exec $dockerContainer sed -i "s/version: .*/version: $tag/" app/config/parameters.yml

# install vendors and assets
docker exec $dockerContainer bash -c "export SYMFONY_ENV=prod"
docker exec $dockerContainer composer install -on # --no-dev
docker exec $dockerContainer bin/console assets:install --env=prod --no-debug
docker exec $dockerContainer bin/console assetic:dump --env=prod --no-debug

# backup DB if prod deploy
#if [ "$env" == "prod" ]; then
#    echo "Backup prod DB"
#    $baseDir/tools/dbBackup.sh
#fi

# migrate DB
docker exec $dockerContainer bin/console doc:mig:mig -n --allow-no-migration

echo "Reset opcache"
curl -s localhost/reset_opcache.php

echo ""
echo "####################################################"
echo "The upgrade to v$tag has been successfully done ;)"
echo "####################################################"