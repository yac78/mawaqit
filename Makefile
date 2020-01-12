.RECIPEPREFIX +=
PATH  := node_modules/.bin:bin/:$(PATH)

.DEFAULT_GOAL := help

.PHONY: help
help:
    @grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install: ## Install project in dev environement
    ./install.sh

stop: ## Stop all containers
    docker-compose stop

kill: ## Kill all containers
    docker-compose kill

down: ## Down all containers
    docker-compose down

up:stop ## Stop and up all containers
    docker-compose up -d --build

restart: ## Restart all containers
    docker-compose restart

dsu: ## Doctrine schema update
    docker-compose exec php bin/console d:s:u --force

fl: ## Fixtures load
    docker-compose exec php bin/console h:f:l -n --purge-with-truncate

mig: ## Create diff migration and migrate
    docker-compose exec php bin/console d:mig:diff
    docker-compose exec php bin/console doc:mig:mig -n --allow-no-migration

cc: ## Symfony cache clear
    docker-compose exec php bash -c "bin/console c:c --no-warmup && bin/console c:w"