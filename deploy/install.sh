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

cd $repoDir

git checkout $branch && git pull origin $branch

echo "Creating symlinks"
ln -snf $repoDir/web/robots.txt.$env $repoDir/web/robots.txt

echo "Set version"
version=dev@`git rev-parse --short HEAD`
sed -i "s/version: .*/version: $version/" app/config/parameters.yml

# install vendors and assets
export SYMFONY_ENV=prod
composer install --no-dev -n -o
bin/console assets:install -e prod --no-debug
bin/console assetic:dump -e prod --no-debug

# migrate DB
bin/console doctrine:migrations:migrate -n --allow-no-migration -e prod

echo "Reset opcache"
curl -s localhost:81/reset_opcache.php

echo ""
echo "####################################################"
echo "The upgrade has been successfully done ;)"
echo "####################################################"