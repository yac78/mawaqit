#!/bin/bash
set -e

env=$1
tag=$2
baseDir=/var/www/mawaqit
repoDir=$baseDir/repo
dockerContainer=php

cd $repoDir

if [ "$env" == "prod" ]; then
    # Sync DB if prod deploy
    echo "Sync DB"
    $baseDir/tools/dbSync.sh
fi


# maintenance
if [ "$env" == "prod" ]; then
    touch $repoDir/docker/data/maintenance
    docker exec mawaqit_nginx nginx -s reload
fi

docker-compose exec $dockerContainer git fetch && git checkout $tag

if [ "$env" == "pp" ]; then
    docker-compose exec $dockerContainer git pull origin $tag
fi

echo "Creating symlinks"
docker-compose exec $dockerContainer sh -c "(cd web && ln -snf robots.txt.$env robots.txt)"

echo "Set version"
version=`git symbolic-ref -q --short HEAD`@`git rev-parse --short    HEAD`
if [ "$env" == "prod" ]; then
    version=$tag
fi

docker-compose exec $dockerContainer sed -i "s/version: .*/version: $version/" app/config/parameters.yml

# Install vendors and assets
docker-compose exec $dockerContainer sh -c "SYMFONY_ENV=$env composer install -o -n --no-dev"
docker-compose exec $dockerContainer bin/console assetic:dump -e $env --no-debug

# Migrate DB
docker-compose exec $dockerContainer bin/console doc:mig:mig -n --allow-no-migration -e $env

# cache
docker-compose exec $dockerContainer bin/console c:c -e $env --no-debug --no-warmup
docker-compose exec $dockerContainer bin/console c:w -e $env --no-debug

# Restart php
docker-compose exec $dockerContainer kill -USR2 1

echo ""
echo "####################################################"
echo "$env has been successfully upgraded to $tag ;)"
echo "####################################################"