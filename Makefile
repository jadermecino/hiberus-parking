current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

install:
	@docker run --rm $(INTERACTIVE) --volume $(current-dir):/app --user $(id -u):$(id -g) \
		composer:2.6.4 install \
			--ignore-platform-reqs \
			--no-ansi

start:
	make create-environment
	docker compose up --build -d

stop:
	docker compose stop

destroy:
	docker compose down

restart:
	docker compose restart

logs:
	docker compose logs -f

shell:
	docker compose exec php bash

phpunit:
	docker compose exec php sh -c "vendor/bin/phpunit"

clean-cache:
	docker compose exec php sh -c "vendor/bin/drush cr"

rebuild:
	docker compose build --pull --force-rm --no-cache
	make install
	make start

migrate-database:
	make create-environment
	docker compose exec php sh -c "php vendor/bin/drush sql-drop -y"
	docker compose exec php sh -c  "php vendor/bin/drush sql-cli < database/database.sql"
	make clean-cache

create-environment:
	@if [ ! -f .env ]; then cp .env.example .env; fi
