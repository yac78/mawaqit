#!/bin/bash

# Exit on first error
set -e

if [ $# -lt 2 ]; then
    echo "env and tag are mandatory"
    exit;
fi

env=$1
tag=$2
baseDir=/var/www/mawaqit
repoDir=$baseDir/repo
sharedDir=$baseDir/shared
envDir=$baseDir/$env
targetDir=$envDir/$tag

cd $repoDir

if [ "$env" == "prod" ]; then
    git fetch && git checkout $tag
fi

if [ "$env" == "pp" ]; then
    git checkout $tag && git pull origin $tag
fi

version=$tag
if [ "$env" == "pp" ]; then
    version=dev@`git rev-parse --short HEAD`
fi

mkdir -p $targetDir

echo "Copying files"
rsync -r --delete --files-from=$repoDir/deploy/files-to-include --exclude-from=$repoDir/deploy/files-to-exclude $repoDir $targetDir

echo "Creating symlinks"
ln -sf $sharedDir/upload $targetDir/web/upload
ln -sf $sharedDir/static $targetDir/web/static
ln -sf $sharedDir/parameters.$env.yml $targetDir/app/config/parameters.yml
ln -sf $sharedDir/robots.txt.$env $targetDir/web/robots.txt

if [ "$env" == "prod" ]; then
    ln -sf $sharedDir/logs $targetDir/var/logs
    ln -sf $sharedDir/sessions $targetDir/var/sessions
fi

cd $targetDir

echo "Set version"
sed -i "s/version: .*/version: $version/" app/config/parameters.yml

# install vendors and assets
export SYMFONY_ENV=prod
composer install --no-dev --optimize-autoloader --no-interaction
bin/console cache:warmup --env=prod
bin/console assets:install --env=prod --no-debug
bin/console assetic:dump --env=prod --no-debug

# backup DB if prod deploy
if [ "$env" == "prod" ]; then
    echo "Backup prod DB"
    $baseDir/tools/dbBackup.sh
fi

# migrate DB
bin/console doctrine:migrations:migrate -n --allow-no-migration

echo "Creating current symlink"
cd $envDir && rm current || true && ln -s $tag current

echo "Reset opcache"
curl -s localhost:81/reset_opcache.php

#echo "Force reload mosques"
#mysql -u mawaqit -p`cat $sharedDir/dbpwd` mawaqit_$env < $repoDir/deploy/update.sql

echo "Deleting old releases, keep 3 latest"
rm -rf `ls -t  | tail -n +5` || true

echo ""
echo "####################################################"
echo "The upgrade to v$tag has been successfully done ;)"
echo "####################################################"