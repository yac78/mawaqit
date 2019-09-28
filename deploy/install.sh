#!/bin/bash
set -e

env=$1
tag=$2
baseDir=/var/www/mawaqit
repoDir=$baseDir/repo
dockerContainer=mawaqit_php

cd $repoDir

# maintenance
if [ "$env" == "prod" ]; then
    touch $repoDir/docker/data/maintenance
fi

if [ "$env" == "prod" ]; then
    # Sync DB if prod deploy
    echo "Sync DB"
    $baseDir/tools/dbSync.sh
fi

docker exec $dockerContainer git fetch && git checkout $tag

if [ "$env" == "pp" ]; then
    docker exec $dockerContainer git pull origin $tag
fi

echo "Creating symlinks"
docker exec $dockerContainer sh -c "(cd web && ln -snf robots.txt.$env robots.txt)"

echo "Set version"
version=`git symbolic-ref -q --short HEAD`@`git rev-parse --short    HEAD`
if [ "$env" == "prod" ]; then
    version=$tag
fi

docker exec $dockerContainer sed -i "s/version: .*/version: $version/" app/config/parameters.yml

# Install vendors and assets
docker exec $dockerContainer sh -c "SYMFONY_ENV=$env composer install -o -n --no-dev"
docker exec $dockerContainer bin/console assets:install -e $env --no-debug
docker exec $dockerContainer bin/console assetic:dump -e $env --no-debug

# Migrate DB
docker exec $dockerContainer bin/console doc:mig:mig -n --allow-no-migration -e $env

# cache
docker exec $dockerContainer bin/console c:c -e $env --no-debug --no-warmup
docker exec $dockerContainer bin/console c:w -e $env --no-debug

# Restart php
docker exec $dockerContainer kill -USR2 1

echo ""
echo "####################################################"
echo "$env has been successfully upgraded to $tag ;)"
echo "####################################################"