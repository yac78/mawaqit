#!/usr/bin/env bash

ln -sf docker-compose.dev.yml docker-compose.yml
sudo rm -rf var/logs/* var/cache/* var/sessions/*
docker-compose kill && docker-compose rm -f && docker-compose up -d --build
docker-compose exec php-fpm  chmod 777 -R var/logs var/cache var/sessions
sleep 10
docker-compose exec php-fpm bin/console d:s:u -f
docker-compose exec php-fpm bin/console h:f:l -n

echo "------------------------------------------"
echo "Install OK"
echo "Site: http://localhost:8101"
echo "Maildev: http://localhost:8103"
echo "------------------------------------------"