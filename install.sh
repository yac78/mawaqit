#!/usr/bin/env bash

ln -sf docker-compose.dev.yml docker-compose.yml
docker-compose kill && docker-compose rm -f && docker-compose up -d --build
sleep 10
docker-compose exec mawaqit_php bin/console d:s:u -f
docker-compose exec mawaqit_php bin/console h:f:l -n

mkdir -p var/logs/ var/cache/ var/sessions/
docker-compose exec mawaqit_php rm -rf var/logs/* var/cache/* var/sessions/*
docker-compose exec mawaqit_php chmod -R 777 var/logs var/cache var/sessions

echo "------------------------------------------"
echo "Install OK"
echo "Site > http://localhost:10001  Credentials > login: local@local.com  password: local"
echo "Maildev: http://localhost:10003"
echo "------------------------------------------"
