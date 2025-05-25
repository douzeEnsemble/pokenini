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

MKCERT := $(shell command -v mkcert 2> /dev/null)

KEY_FILE := ./docker/apache/ssl/cert-key.pem
CERT_FILE := ./docker/apache/ssl/cert.pem

# Misc
.DEFAULT_GOAL = help

## —— 🎵 🐳 The Symfony-docker Makefile 🐳 🎵 ——————————————————————————————————
.PHONY: help
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Directories and files 📁 ————————————————————————————————————————————————————————————————
.env: ## Create .env files (not phony to check the file)
	touch .env
.env.dev.local: ## Create .env.dev.local files (not phony to check the file)
	cp .env.dev .env.dev.local

.PHONY: certs
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
.PHONY: build
build: ## Builds the Docker images
	$(DOCKER_COMP) build

.PHONY: rebuild
rebuild: ## Re-builds the Docker images (build with no cache)
	${DOCKER_COMP} build --no-cache

.PHONY: start
start: install up vendor cc data ## ## Start the project

.PHONY: up
up: ## Up Docker container
	$(DOCKER_COMP) up --wait

.PHONY: install
install: ## Install requirements
install: .env .env.dev.local certs

.PHONY: stop
stop: ## Stop the project
	$(DOCKER_COMP) down --remove-orphans

.PHONY: destruct
destruct: ## Destruct the project
	$(DOCKER_COMP) down --remove-orphans --volumes database --volumes redis --rmi all


.PHONY: bash
bash: ## Connect to the PHP FPM container
	@$(PHP_CONT) bash

.PHONY: logs
logs: ## Containers logs
	@$(DOCKER_COMP) logs -f -n 0

.PHONY: mocks_restart
mocks_restart: ## Restart mocks
	$(DOCKER_COMP) restart api.int.moco
	$(DOCKER_COMP) restart api.test.moco
	$(DOCKER_COMP) restart web.dev.moco
	$(DOCKER_COMP) restart web.test.moco

## —— Data 💾 ————————————————————————————————————————————————————————————————
.PHONY: data
data: ## Initialize data
data: init_db data_app

.PHONY: init_db
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

.PHONY: data_app
data_app: ## Initialize app data
	$(SYMFONY) app:update:labels
	$(SYMFONY) app:update:games_collections_and_dex
	$(SYMFONY) app:update:pokemons
	$(SYMFONY) app:update:regional_dex_numbers
	$(SYMFONY) app:update:games_availabilities
	$(SYMFONY) app:update:games_shinies_availabilities
	$(SYMFONY) app:update:collections_availabilities
	$(SYMFONY) app:calculate:game_bundles_availabilities
	$(SYMFONY) app:calculate:game_bundles_shinies_availabilities
	$(SYMFONY) app:calculate:dex_availabilities
	$(SYMFONY) app:calculate:pokemon_availabilities

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
.PHONY: composer
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

.PHONY: vendor
vendor: ## Install vendors according to the current composer.lock file
	@$(COMPOSER) install --prefer-dist --no-progress --no-interaction
	@$(COMPOSER) clear-cache

.PHONY: updates
updates: ## Updates all composer
	@$(COMPOSER) update --bump-after-update --with-all-dependencies --optimize-autoloader 
	@$(COMPOSER) update --bump-after-update --with-all-dependencies --optimize-autoloader --working-dir=tools/php-cs-fixer 
	@$(COMPOSER) update --bump-after-update --with-all-dependencies --optimize-autoloader --working-dir=tools/phpmd 
	@$(COMPOSER) update --bump-after-update --with-all-dependencies --optimize-autoloader --working-dir=tools/psalm 
	@$(COMPOSER) update --bump-after-update --with-all-dependencies --optimize-autoloader --working-dir=tools/phpstan 
	@$(COMPOSER) update --bump-after-update --with-all-dependencies --optimize-autoloader --working-dir=tools/infection 
	@$(COMPOSER) update --bump-after-update --with-all-dependencies --optimize-autoloader --working-dir=tools/jsonlint 
	@$(COMPOSER) update --bump-after-update --with-all-dependencies --optimize-autoloader --working-dir=tools/deptrac 
	@$(COMPOSER) update --bump-after-update --with-all-dependencies --optimize-autoloader --working-dir=tools/phpinsights 

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
.PHONY: sf
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

.PHONY: cc
cc: ## Clear the cache
	@$(SYMFONY) cache:clear --env=dev
	@$(SYMFONY) cache:clear --env=test

## —— Tests 🧪 ———————————————————————————————————————————————————————————————
.PHONY: tests
tests: ## Execute all tests
tests: tests_api tests_web

.PHONY: tests_api
tests_api: ## Execute unit test for Api module
	@$(PHP) bin/console doctrine:schema:update --force --env=test
	$(PHP) vendor/bin/phpunit tests/Api

.PHONY: tests_web
tests_web: ## Execute unit test for Web module
	@$(PHP) bin/console doctrine:schema:update --force --env=test
	$(PHP) vendor/bin/phpunit tests/Web

.PHONY: rebuild
tests_unit_api: ## Execute unit tests for Api module
	@$(PHP_CONT) vendor/bin/phpunit tests/Api/Unit

.PHONY: tests_unit_web
tests_unit_web: ## Execute unit tests for Web module
	@$(PHP_CONT) vendor/bin/phpunit tests/Web/Unit

.PHONY: tests_functional_api
tests_functional_api: ## Execute functional tests for Api module
	@$(PHP_CONT) vendor/bin/phpunit tests/Api/Functional

.PHONY: tests_functional_web
tests_functional_web: ## Execute functional tests for Web module
	@$(PHP_CONT) vendor/bin/phpunit tests/Web/Functional

.PHONY: tests_browser_web
tests_browser_web: ## Execute browser tests for Web module
	@$(PHP_CONT) vendor/bin/phpunit tests/Web/Browser

## —— Quality 👌 ———————————————————————————————————————————————————————————————
.PHONY: quality
quality: ## Execute all quality analyses
quality: phpcsfixer phpmd psalm phpstan deptrac

.PHONY: phpcsfixer
phpcsfixer: ## Execute PHP CS Fixer "Check"
phpcsfixer: tools/php-cs-fixer/vendor/bin/php-cs-fixer
	@$(PHP) tools/php-cs-fixer/vendor/bin/php-cs-fixer check --diff

.PHONY: phpcsfixer_fix
phpcsfixer_fix: ## Execute PHP CS Fixer "Fix"
phpcsfixer_fix: tools/php-cs-fixer/vendor/bin/php-cs-fixer
	@$(PHP) tools/php-cs-fixer/vendor/bin/php-cs-fixer fix

.PHONY: phpmd
phpmd: ## Execute phpmd
phpmd: tools/phpmd/vendor/bin/phpmd
	@$(PHP) tools/phpmd/vendor/bin/phpmd src,tests text ruleset.xml

.PHONY: psalm
psalm: ## Execute psalm
psalm: tools/psalm/vendor/bin/psalm
	@$(PHP_CONT) rm -Rf var/cache/psalm
	@$(PHP) tools/psalm/vendor/bin/psalm --show-info=false

.PHONY: psalm_fix
psalm_fix: ## Execute psalm auto fixing
psalm_fix: tools/psalm/vendor/bin/psalm
	@$(PHP) tools/psalm/vendor/bin/psalm --alter --issues=UnnecessaryVarAnnotation,UnusedVariable,PossiblyUnusedMethod,MissingParamType

.PHONY: phpstan
phpstan: ## Execute phpstan analyse
phpstan: tools/phpstan/vendor/bin/phpstan
	@$(PHP) tools/phpstan/vendor/bin/phpstan clear-result-cache
	@$(PHP) tools/phpstan/vendor/bin/phpstan analyse --memory-limit=-1

.PHONY: deptrac
deptrac: ## Execute deptrac analyse
deptrac: tools/deptrac/vendor/bin/deptrac
	@$(PHP) tools/deptrac/vendor/bin/deptrac analyse

.PHONY: phpinsights
phpinsights: ## Execute phpinsights
phpinsights: tools/phpinsights/vendor/bin/phpinsights
	@$(PHP) tools/phpinsights/vendor/bin/phpinsights

## —— Integration 🗂️ ———————————————————————————————————————————————————————————————
.PHONY: integration
integration: ## Execute all integration tests
integration: newman

.PHONY: newman
newman: ## Execute newman
newman: newman_prepare newman_execute

.PHONY: newman_prepare
newman_prepare:
	@$(SYMFONY) --env=int app:update:labels
	@$(SYMFONY) --env=int app:update:games_collections_and_dex
	@$(SYMFONY) --env=int app:update:pokemons
	@$(SYMFONY) --env=int app:update:regional_dex_numbers
	@$(SYMFONY) --env=int app:update:games_availabilities
	@$(SYMFONY) --env=int app:update:games_shinies_availabilities
	@$(SYMFONY) --env=int app:update:collections_availabilities
	@$(SYMFONY) --env=int app:calculate:game_bundles_availabilities
	@$(SYMFONY) --env=int app:calculate:game_bundles_shinies_availabilities
	@$(SYMFONY) --env=int app:calculate:dex_availabilities
	@$(SYMFONY) --env=int app:calculate:pokemon_availabilities

.PHONY: newman_execute
newman_execute:
	$(DOCKER) run --rm --name pokenini-newman \
		--network=pokenini_default \
		-v ./tests/Api/Integration:/etc/newman \
		-t postman/newman:alpine run collection.json

## —— Measures 📏 ———————————————————————————————————————————————————————————————
.PHONY: measures
measures: ## Execute all measures tools
measures: clear_build coverage infection_api infection_web

.PHONY: clear_build
clear_build: ## Clear build directory
	rm -Rf build/*

build/coverage/coverage-xml: ## Generate coverage report
	$(DOCKER_COMP) exec \
		-e XDEBUG_MODE=coverage -T php \
		php vendor/bin/phpunit \
            --exclude-group="browser-testing" \
			--coverage-clover=coverage.xml \
			--coverage-xml=build/coverage/coverage-xml \
			--log-junit=build/coverage/junit.xml

.PHONY: coverage
coverage: ## Execute PHPUnit Coverage to check the score
coverage: build/coverage/coverage-xml
	@$(PHP_CONT) php tests/tools/coverage.php coverage.xml 100 true

.PHONY: coverage_html
coverage_html: ## Execute PHPUnit Coverage in HTML
	$(DOCKER_COMP) exec \
		-e XDEBUG_MODE=coverage -T php \
		php vendor/bin/phpunit \
            --exclude-group="browser-testing" \
			--coverage-html=build/coverage/coverage-html

.PHONY: clear_infection_cache
clear_infection_cache: 
	@$(PHP_CONT) rm -Rf var/cache/infection

.PHONY: infection
infection: ## Execute all Infection testing
infection: clear_infection_cache infection_api infection_web

.PHONY: infection_api
infection_api: ## Execute Infection (Mutation testing) for API module
infection_api: build/coverage/coverage-xml tools/infection/vendor/bin/infection clear_infection_cache
	@$(PHP) tools/infection/vendor/bin/infection --threads=4 --no-progress \
		--skip-initial-tests --coverage=build/coverage \
		--min-msi=100 --min-covered-msi=100 \
		--filter=src/Api

.PHONY: infection_web
infection_web: ## Execute Infection (Mutation testing) for API module
infection_web: build/coverage/coverage-xml tools/infection/vendor/bin/infection clear_infection_cache
	@$(PHP) tools/infection/vendor/bin/infection --threads=4 --no-progress \
		--skip-initial-tests --coverage=build/coverage \
		--min-msi=100 --min-covered-msi=100 \
		--filter=src/Web

## —— Security 🛡️ ———————————————————————————————————————————————————————————————
.PHONY: security
security: ## Execute all security commands
security: composer_audit security_checker

.PHONY: composer_audit
composer_audit: ## Execute Composer Audit
	@$(COMPOSER) audit

bin/local-php-security-checker: ## Download the file if needed
	wget https://github.com/fabpot/local-php-security-checker/releases/download/v2.1.3/local-php-security-checker_linux_amd64 -O bin/local-php-security-checker
	chmod a+x bin/local-php-security-checker

.PHONY: security_checker
security_checker: ## Execute Security Checker
security_checker: bin/local-php-security-checker
	bin/local-php-security-checker

.PHONY: dependency_check
dependency_check: ## Execute OWASP Dependency Check
dependency_check: 
	@bin/dependency-check.sh ${NVD_API_KEY}

## —— Tools 🔧 ———————————————————————————————————————————————————————————————
tools/php-cs-fixer/vendor/bin/php-cs-fixer: ## Install php-cs-fixer
	@$(COMPOSER) install --working-dir=tools/php-cs-fixer --optimize-autoloader --no-dev

tools/phpmd/vendor/bin/phpmd: ## Install phpmd
	@$(COMPOSER) install --working-dir=tools/phpmd --optimize-autoloader --no-dev

tools/psalm/vendor/bin/psalm: ## Install psalm
	@$(COMPOSER) install --working-dir=tools/psalm --optimize-autoloader --no-dev

tools/phpstan/vendor/bin/phpstan: ## Install phpstan
	@$(COMPOSER) install --working-dir=tools/phpstan --optimize-autoloader --no-dev

tools/infection/vendor/bin/infection: ## Install infection
	@$(COMPOSER) install --working-dir=tools/infection --optimize-autoloader --no-dev

tools/deptrac/vendor/bin/deptrac: ## Install deptrac
	@$(COMPOSER) install --working-dir=tools/deptrac --optimize-autoloader --no-dev

tools/phpinsights/vendor/bin/phpinsights: ## Install phpinsights
	@$(COMPOSER) install --working-dir=tools/phpinsights --optimize-autoloader --no-dev


## —— Image 🐳 ———————————————————————————————————————————————————————————————
img-build: ## Build Docker image
	docker build --target php_prod -f ./docker/php/Dockerfile -t ghcr.io/douzeensemble/pokenini:latest .
	docker push ghcr.io/douzeensemble/pokenini:latest