#!/bin/bash
set -e

env=$1
tag=$2
baseDir=/var/www/mawaqit
repoDir=$baseDir/repo
dockerContainer=php-fpm

cd $repoDir

if [ "$env" == "prod" ]; then
    # maintenance
    touch $repoDir/docker/data/maintenance

    # Sync DB if prod deploy
    echo "Sync DB"
    $baseDir/tools/dbSync.sh
fi

docker-compose exec -T $dockerContainer git fetch && git checkout $tag

if [ "$env" == "pp" ]; then
    docker-compose exec -T $dockerContainer git pull origin $tag
fi

echo "Creating symlinks"
docker-compose exec -T $dockerContainer sh -c "(cd web && ln -snf robots.txt.$env robots.txt)"

echo "Set version"
version=dev@`git rev-parse --short HEAD`
if [ "$env" == "prod" ]; then
    version=$tag
fi

docker-compose exec -T $dockerContainer sed -i "s/version: .*/version: $version/" app/config/parameters.yml

# Install vendors and assets
docker-compose exec -T $dockerContainer sh -c "SYMFONY_ENV=$env composer install -o -n --no-dev"
docker-compose exec -T $dockerContainer bin/console assets:install -e $env --no-debug
docker-compose exec -T $dockerContainer bin/console assetic:dump -e $env --no-debug

# Migrate DB
docker-compose exec -T $dockerContainer bin/console doc:mig:mig -n --allow-no-migration -e $env

# cache
docker-compose exec -T $dockerContainer bin/console c:c -e $env --no-debug --no-warmup
docker-compose exec -T $dockerContainer bin/console c:w -e $env --no-debug

# Restart php
docker-compose exec -T $dockerContainer kill -USR2 1

echo ""
echo "####################################################"
echo "$env has been successfully upgraded to $tag ;)"
echo "####################################################"