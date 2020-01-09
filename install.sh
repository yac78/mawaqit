#!/usr/bin/env bash

ln -sf docker-compose.dev.yml docker-compose.yml
docker-compose kill && docker-compose up -d --build
docker-compose exec php -u 1001 composer install -n
docker-compose exec php chown -R www-data var web/upload

echo ""
echo "Waiting for database..."

while ! docker-compose exec db mysqladmin ping -h"127.0.0.1" --silent; do
    sleep 1
done

docker-compose exec php bin/console d:s:u -f
docker-compose exec php bin/console h:f:l -n

echo "------------------------------------------"
echo "Mawaqit is up"
echo "http://localhost:10001 / login: local@local.com / password: local"
echo "Database: host 127.0.0.1, port 10002, user root, password mawaqit"
echo "Maildev: http://localhost:10003"
echo "------------------------------------------"
