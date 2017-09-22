#!/bin/bash
if [ -z "$1" ]; then
echo "The branch to deploy is mandatory";
exit 1
fi

if [ -z "$2" ]; then
echo "The tag to create is mandatory";
exit 1
fi

if [ -z "$3" ]; then
echo "The message is mandatory";
exit 1
fi

git tag $2 -m "$3"
git push
git push --tags

rm -rf /tmp/prayer-times-v3
mkdir -p /tmp/prayer-times-v3

git archive $1 | (cd /tmp/prayer-times-v3 && tar xf -)


mkdir -p ~/www/prayer-times-v3/$1
rsync -r --force --files-from=bin/deploy/files-to-package --exclude-from=bin/deploy/files-to-exclude /tmp/prayer-times-v3 ~/www/prayer-times-v3/$1
cd ~/www/prayer-times-v3/$1/docker
cp ~/perso/projects/prayer-times-v3/docker/docker-compose.deploy.yml docker-compose.yml 
cp ~/perso/projects/prayer-times-v3/app/config/parameters.prod.yml ~/www/prayer-times-v3/$1/app/config/parameters.prod.yml
sed -i "s/version.*/version: $2/g" ~/www/prayer-times-v3/$1/app/config/parameters.prod.yml
docker-compose up -d

cd ..
./dock-deploy bash -c 'export SYMFONY_ENV=prod'
./dock-deploy composer install --no-dev --optimize-autoloader
./dock-deploy php bin/console assets:install --env=prod --no-debug
./dock-deploy php bin/console assetic:dump --env=prod --no-debug

./dock-deploy chmod -R 777 var/cache var/logs var/sessions
rm -rf bin docker dock-deploy composer.* app/config/routing_dev.yml app/config/config_dev.yml app/config/parameters.yml.dist

cd ..

rm -f current
ln -s $1 current
