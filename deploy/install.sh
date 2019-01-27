#!/bin/bash
set -e

env=$1
tag=$2
baseDir=/var/www/mawaqit
repoDir=$baseDir/repo
dockerContainer=mawaqit
dockerUser=1001:1001

cd $repoDir

docker exec --user $dockerUser $dockerContainer git fetch && git checkout $tag

if [ "$env" == "pp" ]; then
    docker exec --user $dockerUser $dockerContainer git pull origin $tag
fi

echo "Creating symlinks"
docker exec --user $dockerUser $dockerContainer sh -c "(cd web && ln -snf robots.txt.$env robots.txt)"

echo "Set version"
version=dev@`git rev-parse --short HEAD`
if [ "$env" == "prod" ]; then
    version=$tag
fi

docker exec --user $dockerUser $dockerContainer sed -i "s/version: .*/version: $version/" app/config/parameters.yml

# Install vendors and assets
docker exec --user $dockerUser $dockerContainer sh -c "SYMFONY_ENV=prod composer install -o -n --no-dev"
docker exec --user $dockerUser $dockerContainer bin/console assets:install -e $env --no-debug
docker exec --user $dockerUser $dockerContainer bin/console assetic:dump -e $env --no-debug

# Migrate DB
docker exec --user $dockerUser $dockerContainer bin/console doc:mig:mig -n --allow-no-migration -e $env

# Restart php
docker exec $dockerContainer kill -USR2 1

# Sync DB if prod deploy
if [ "$env" == "prod" ]; then
    echo "Sync DB"
    $baseDir/tools/dbSync.sh
fi

echo ""
echo "####################################################"
echo "$env as been successfully upgraded to $tag ;)"
echo "####################################################"