.RECIPEPREFIX +=
PATH  := node_modules/.bin:bin/:$(PATH)

install:
    ./install.sh

stop:
    docker-compose stop

down:
    docker-compose down

up:
    docker-compose down && docker-compose up -d --build

restart:
    docker-compose restart


dsu: # fixtures load
    docker-compose exec php bin/console d:s:u --force

fl: # fixtures load
    docker-compose exec php bin/console h:f:l -n --purge-with-truncate

mig:
    docker-compose exec php bin/console make:migration
    docker-compose exec php bin/console doc:mig:mig -n --allow-no-migration

cc:
    docker-compose exec php bash -c "bin/console c:c --no-warmup && bin/console c:w"