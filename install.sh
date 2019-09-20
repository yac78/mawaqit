#!/usr/bin/env bash

ln -s docker-compose.dev.yml docker-compose.yml

docker-compose kill && docker-compose rm -f && docker-compose up -d --build
docker-compose exec mawaqit composer install -n
docker-compose exec mawaqit chmod 777 -R var/logs var/cache var/sessions
sleep 5
docker-compose exec mawaqit bin/console d:s:u -f
docker-compose exec mawaqit bin/console h:f:l -n

echo "------------------------------------------"
echo "Install OK, Go to http://localhost:8101"
echo "------------------------------------------"

