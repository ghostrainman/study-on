COMPOSE=docker compose
PHP=$(COMPOSE) exec php
CONSOLE=$(PHP) bin/console
COMPOSER=$(PHP) composer

up:
	@${COMPOSE} up -d

down:
	@${COMPOSE} down

encore_dev:
	@${COMPOSE} run node yarn encore dev

encore_prod:
	@${COMPOSE} run node yarn encore production

encore_watch:
	@${COMPOSE} run node yarn encore dev --watch

clear:
	@${CONSOLE} cache:clear

migration:
	@${CONSOLE} make:migration

migrate:
	@${CONSOLE} doctrine:migrations:migrate

fixtload:
	@${CONSOLE} doctrine:fixtures:load

yarn_install:
	@${COMPOSE} run node yarn install

clear_prod:
	@${PHP} bash -c "APP_ENV=prod php bin/console cache:clear"
	@${PHP} bash -c "APP_ENV=prod php bin/console cache:warmup"

clear_dev:
	@${PHP} bash -c "APP_ENV=dev php bin/console cache:clear"
	@${PHP} bash -c "APP_ENV=dev php bin/console cache:warmup"
-include local.mk
