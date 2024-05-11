# Executables (local)
DOCKER = docker
DOCKER_COMP = docker compose

# Docker containers
ifeq (${CI}, true)
DOCKER_COMP_EXEC = $(DOCKER_COMP) exec -T
else
DOCKER_COMP_EXEC = $(DOCKER_COMP) exec
endif

PHP_CONT = $(DOCKER_COMP_EXEC) php
DATABASE_CONT = $(DOCKER_COMP_EXEC) database

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP_CONT) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY : help
.PHONY : certs build rebuild up install start stop sh
.PHONY : data init_db data_app
.PHONY : composer vendor sf cc
.PHONY : tests tests_api tests_web tests_unit_api tests_unit_web tests_functional_api tests_functional_web tests_browser_web
.PHONY : quality phpcs phpcbf phpmd psalm phpstan deptrac
.PHONY : integration newman
.PHONY : measures clear-build coverage htmlcoverage infection infection_api infection_web

## —— 🎵 🐳 The Symfony-docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Directories and files 🐳 ————————————————————————————————————————————————————————————————
.env: ## Create .env files (not phony to check the file)
	touch .env
.env.dev.local: ## Create .env.dev.local files (not phony to check the file)
	cp .env.dev .env.dev.local

MKCERT := $(shell command -v mkcert 2> /dev/null)

KEY_FILE := ./docker/apache/ssl/cert-key.pem
CERT_FILE := ./docker/apache/ssl/cert.pem

certs: ## Create ssl files
certs:
	mkdir -p ./docker/apache/ssl
	@if [ -z "$(MKCERT)" ]; then \
		echo "mkcert is not installed in your system. Please install it"; \
		exit 1; \
	fi
	@if [ ! -e $(KEY_FILE) ] || [ ! -e $(CERT_FILE) ]; then \
		mkcert \
			-key-file $(KEY_FILE) \
			-cert-file $(CERT_FILE) \
			localhost 127.0.0.1 ::1 ; \
	fi

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	$(DOCKER_COMP) build
rebuild: ## Re-builds the Docker images (build with no cache)
	${DOCKER_COMP} build --no-cache

start: install up vendor cc data ## ## Start the project

up: ## Up Docker container
	$(DOCKER_COMP) up --wait

install: ## Install requirements
install: .env .env.dev.local certs

stop: ## Stop the project
	$(DOCKER_COMP) down --remove-orphans

sh: ## Connect to the PHP FPM container
	@$(PHP_CONT) bash

## —— Data 💾 ————————————————————————————————————————————————————————————————
data: ## Initialize data
data: init_db data_app

init_db: ## Initialize database data
	$(SYMFONY) doctrine:database:drop --force --if-exists --env=dev
	$(SYMFONY) doctrine:database:create --env=dev
	$(SYMFONY) doctrine:migration:migrate --no-interaction --env=dev
	$(SYMFONY) doctrine:database:drop --force --if-exists --env=test
	$(SYMFONY) doctrine:database:create --env=test
	$(SYMFONY) doctrine:migration:migrate --no-interaction --env=test
	$(SYMFONY) doctrine:database:drop --force --if-exists --env=int
	$(SYMFONY) doctrine:database:create --env=int
	$(SYMFONY) doctrine:migration:migrate --no-interaction --env=int

data_app: ## Initialize app data
	$(SYMFONY) app:update:labels
	$(SYMFONY) app:update:games_and_dex
	$(SYMFONY) app:update:pokemons
	$(SYMFONY) app:update:regional_dex_numbers
	$(SYMFONY) app:update:games_availabilities
	$(SYMFONY) app:update:games_shinies_availabilities
	$(SYMFONY) app:calculate:game_bundles_availabilities
	$(SYMFONY) app:calculate:game_bundles_shinies_availabilities
	$(SYMFONY) app:calculate:dex_availabilities
	$(SYMFONY) app:calculate:pokemon_availabilities

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
	@$(COMPOSER) install --prefer-dist --no-progress --no-interaction
	@$(COMPOSER) clear-cache

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: ## Clear the cache
	@$(SYMFONY) cache:clear --env=dev
	@$(SYMFONY) cache:clear --env=test

## —— Tests 🧪 ———————————————————————————————————————————————————————————————
tests: ## Execute all tests
tests: tests_api tests_web

tests_api: ## Execute unit test for Api module
	@$(PHP) bin/console doctrine:schema:update --complete --force --env=test
	$(PHP) vendor/bin/phpunit tests/Api

tests_web: ## Execute unit test for Web module
	@$(PHP) bin/console doctrine:schema:update --complete --force --env=test
	$(PHP) vendor/bin/phpunit tests/Web

tests_unit_api: ## Execute unit tests for Api module
	@$(PHP_CONT) vendor/bin/phpunit tests/Api/Unit

tests_unit_web: ## Execute unit tests for Web module
	@$(PHP_CONT) vendor/bin/phpunit tests/Web/Unit

tests_functional_api: ## Execute functional tests for Api module
	@$(PHP_CONT) vendor/bin/phpunit tests/Api/Functional

tests_functional_web: ## Execute functional tests for Web module
	@$(PHP_CONT) vendor/bin/phpunit tests/Web/Functional

tests_browser_web: ## Execute browser tests for Web module
	@$(PHP_CONT) vendor/bin/phpunit tests/Web/Browser

## —— Quality 👌 ———————————————————————————————————————————————————————————————
quality: ## Execute all quality analyses
quality: phpcs phpmd psalm phpstan deptrac

phpcs: ## Execute phpcs
	@$(PHP) vendor/bin/phpcs
phpcbf: ## Execute phpcbf (code beautifier) /!\ This could edit your code
	@$(PHP) vendor/bin/phpcbf

phpmd: ## Execute phpmd
	@$(PHP) vendor/bin/phpmd src,tests text ruleset.xml

psalm: ## Execute psalm
	@$(PHP) vendor/bin/psalm --show-info=false

phpstan: ## Execute phpstan analyse
	@$(PHP) vendor/bin/phpstan clear-result-cache
	@$(PHP) vendor/bin/phpstan analyse --memory-limit=-1

deptrac: ## Execute deptrac analyse
	@$(PHP) vendor/bin/deptrac analyse

## —— Integration 🗂️ ———————————————————————————————————————————————————————————————
integration: ## Execute all integration tests
integration: newman

newman: newman_prepare newman_execute## Execute newman

newman_prepare:
	@$(SYMFONY) --env=int app:update:labels
	@$(SYMFONY) --env=int app:update:games_and_dex
	@$(SYMFONY) --env=int app:update:pokemons
	@$(SYMFONY) --env=int app:update:regional_dex_numbers
	@$(SYMFONY) --env=int app:update:games_availabilities
	@$(SYMFONY) --env=int app:update:games_shinies_availabilities
	@$(SYMFONY) --env=int app:calculate:game_bundles_availabilities
	@$(SYMFONY) --env=int app:calculate:game_bundles_shinies_availabilities
	@$(SYMFONY) --env=int app:calculate:dex_availabilities
	@$(SYMFONY) --env=int app:calculate:pokemon_availabilities

newman_execute:
	$(DOCKER) run --rm --name pokenini-newman \
		--network=pokenini_default \
		-v ./tests/Api/Integration:/etc/newman \
		-t postman/newman:alpine run collection.json

## —— Measures 📏 ———————————————————————————————————————————————————————————————
measures: ## Execute all measures tools
measures: clear-build coverage infection_api infection_web

clear-build: # Clear build directory
	rm -Rf build/*

build/coverage/coverage-xml: ## Generate coverage report
	$(DOCKER_COMP) exec \
		-e XDEBUG_MODE=coverage -T php \
		php vendor/bin/phpunit \
            --exclude-group="browser-testing" \
			--coverage-clover=coverage.xml \
			--coverage-xml=build/coverage/coverage-xml \
			--log-junit=build/coverage/junit.xml

coverage: ## Execute PHPUnit Coverage to check the score
coverage: build/coverage/coverage-xml
	@$(PHP_CONT) php tests/tools/coverage.php coverage.xml 100 true

htmlcoverage: ## Execute PHPUnit Coverage in HTML
	$(DOCKER_COMP) exec \
		-e XDEBUG_MODE=coverage -T php \
		php vendor/bin/phpunit --coverage-html=build/coverage/coverage-html

infection: ## Execute all Infection testing
infection: infection_api infection_web

infection_api: ## Execute Infection (Mutation testing) for API module
infection_api: build/coverage/coverage-xml 
	@$(PHP) vendor/bin/infection --threads=4 --show-mutations \
		--min-msi=100 --min-covered-msi=100 \
		--logger-html='tests/mutation/index.html' \
		--filter=src/Api

infection_web: ## Execute Infection (Mutation testing) for API module
infection_web: build/coverage/coverage-xml 
	@$(PHP) vendor/bin/infection --threads=4 --show-mutations \
		--min-msi=100 --min-covered-msi=100 \
		--logger-html='tests/mutation/index.html' \
		--filter=src/Web
