#!/bin/bash

# Exit on first error
set -e

if [ $# -lt 2 ]; then
    echo "env and branch are mandatory"
    exit;
fi

env=$1
branch=$2
baseDir=/var/www/mawaqit
repoDir=$baseDir/repo
sharedDir=$baseDir/shared
envDir=$baseDir/$env
targetDir=$envDir/master

cd $repoDir

if [ "$env" == "prod" ]; then
    git fetch && git checkout $branch && git pull origin $branch
fi

version=dev@`git rev-parse --short HEAD`

mkdir -p $targetDir

echo "Copying files"
rsync -r --delete --files-from=$repoDir/deploy/files-to-include --exclude-from=$repoDir/deploy/files-to-exclude $repoDir $targetDir

echo "Creating symlinks"
ln -snf $sharedDir/upload/ $targetDir/web/upload
ln -snf $sharedDir/static $targetDir/web/static
ln -snf $sharedDir/parameters.$env.yml $targetDir/app/config/parameters.yml
ln -snf $sharedDir/robots.txt.$env $targetDir/web/robots.txt

if [ "$env" == "prod" ]; then
    ln -snf $sharedDir/logs $targetDir/var/logs
    ln -snf $sharedDir/sessions $targetDir/var/sessions
fi

cd $targetDir

echo "Set version"
sed -i "s/version: .*/version: $version/" app/config/parameters.yml

# install vendors and assets
export SYMFONY_ENV=prod
composer install --no-dev --optimize-autoloader --no-interaction
bin/console assets:install --env=prod --no-debug
bin/console assetic:dump --env=prod --no-debug

# migrate DB
bin/console doctrine:migrations:migrate -n --allow-no-migration

echo "Creating current symlink"
cd $envDir && rm current || true && ln -s $targetDir current

echo "Reset opcache"
curl -s localhost:81/reset_opcache.php

echo ""
echo "####################################################"
echo "The upgrade has been successfully done ;)"
echo "####################################################"