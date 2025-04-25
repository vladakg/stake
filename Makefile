ENGINE_EXEC=docker-compose exec engine

#######################
# DEV TASKS
#######################

init:
	test -f docker-compose.yaml || cp docker-compose.yaml.dist docker-compose.yaml
	docker-compose build
	docker-compose up -d --force-recreate
	${ENGINE_EXEC} php composer.phar install --prefer-dist --no-progress --no-interaction --ansi # to install vendor bundles.
	make create_db
	make run_migrations_fresh

start:
	docker-compose up -d --force-recreate
	${ENGINE_EXEC} php composer.phar install --prefer-dist --no-progress --no-interaction --ansi # to install vendor bundles.
	make run_migrations

stop: ## Stop all containers properly
	docker-compose stop

create_db: ## Create db
	${ENGINE_EXEC} php artisan app:setup-db

run_migrations: ## Run laravel migrations
	${ENGINE_EXEC} php artisan migrate

run_migrations_fresh: ## Run laravel migrate:fresh and seed
	${ENGINE_EXEC} php artisan migrate:fresh
	${ENGINE_EXEC} php artisan db:seed

reset_db: ## Create db
	make create_db
	make run_migrations_fresh

update_currencies: ## Run UpdateCurrenciesCommand
	${ENGINE_EXEC} php artisan app:currencies-update

test: ## Create db
	${ENGINE_EXEC} php artisan test
