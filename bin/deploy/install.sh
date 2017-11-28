#!/bin/bash

# Exit on first error
set -e

if [ $# -lt 2 ]; then
    echo "env and tag are mandatory"
    exit;
fi

env=$1
tag=$2
repoDir=/var/www/mawaqit/repo
deployDir=/var/www/mawaqit/deploy
sharedDir=/var/www/mawaqit/shared
envDir=/var/www/mawaqit/$env
targetDir=$envDir/$tag
  
cd $repoDir
git fetch
git checkout $tag

mkdir -p $targetDir

rsync -r --force --delete --files-from=$deployDir/files-to-include --exclude-from=$deployDir/files-to-exclude $repoDir $targetDir

ln -s $sharedDir/upload/ $targetDir/web/upload || true
ln -s $sharedDir/logs/ $targetDir/var/logs || true
ln -s $sharedDir/sessions/ $targetDir/var/sessions || true
ln -s $sharedDir/parameters.$env.yml $targetDir/app/config/parameters.yml || true

cd $targetDir

# set version
sed -i "s/version: .*/version: $tag/" app/config/parameters.yml


# install vendors and assets
export SYMFONY_ENV=prod
composer install --no-dev --optimize-autoloader --no-interaction
bin/console cache:warmup --env=prod
bin/console assets:install --env=prod --no-debug
bin/console assetic:dump --env=prod --no-debug

# migrate DB
bin/console doctrine:migrations:migrate -n --allow-no-migration

# creating current symlink
rm $envDir/current || true
ln -s $targetDir $envDir/current
    
# Deleting old releases, keep 3 latest
cd $envDir
rm -r `ls -t  | tail -n +5`

echo "The upgrade to v$tag has been successfully done ;)"
