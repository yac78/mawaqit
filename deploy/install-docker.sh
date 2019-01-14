#!/bin/bash
set -e

env=$1
tag=$2
baseDir=/var/www/mawaqit
repoDir=$baseDir/repo

cd $repoDir

dock git fetch && git checkout $tag

echo "Creating symlinks"
dock sh -c "(cd web && ln -snf robots.txt.$env robots.txt)"

echo "Set version"
dock sed -i "s/version: .*/version: $tag/" app/config/parameters.yml

# Install vendors and assets
dock sh -c "SYMFONY_ENV=prod composer install -on --no-dev"
dock bin/console assets:install -e prod --no-debug
dock bin/console assetic:dump -e prod --no-debug

# Fix permissions
dock chmod -R 777 var/cache var/logs var/sessions

# Sync DB if prod deploy
if [ "$env" == "prod" ]; then
    echo "Sync DB"
    $baseDir/tools/dbSync.sh
fi

# Migrate DB
dock bin/console doc:mig:mig -n --allow-no-migration -e prod

# Restart php
dock kill -USR2 1

echo ""
echo "####################################################"
echo "The upgrade to $tag has been successfully done ;)"
echo "####################################################"