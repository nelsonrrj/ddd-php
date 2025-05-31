# Variables
DOCKER_COMPOSE = docker-compose
PHP = $(DOCKER_COMPOSE) exec app php
COMPOSER = $(DOCKER_COMPOSE) exec app composer
MYSQL = $(DOCKER_COMPOSE) exec db mysql -unelsonrrj -pnelsonrrj my_database
MYSQL_TEST = $(DOCKER_COMPOSE) exec db mysql -unelsonrrj -pnelsonrrj my_database_test

# Main commands
.PHONY: up down build test test-unit migrate migrate-test db-reset logs logs-db

# Start the application
up:
	$(DOCKER_COMPOSE) up -d --build

# Stop and delete containers
down:
	$(DOCKER_COMPOSE) down -v

# Rebuild images
build:
	$(DOCKER_COMPOSE) build --no-cache

# Run all tests
test: migrate-test
	$(PHP) vendor/bin/phpunit

# Run only unit tests
test-unit:
	$(PHP) vendor/bin/phpunit tests/Unit

# Database migrations development
migrate:
	$(PHP) src/Infrastructure/Persistence/command.php orm:schema-tool:create

# Database migrations testing
migrate-test:
	$(PHP) tests/Config/command.php orm:schema-tool:create

# Reset database
db-reset:
	$(MYSQL) -e "DROP DATABASE my_database; CREATE DATABASE my_database;"
	$(MYSQL_TEST) -e "DROP DATABASE my_database_test; CREATE DATABASE my_database_test;"
	$(MAKE) migrate
	$(MAKE) migrate-test

# Install dependencies, create .env file and run migrations
install:
	$(MAKE) create-env
	$(MAKE) up
	$(COMPOSER) install --optimize-autoloader
	
	$(MAKE) migrate

# Update autoload
dump:
	$(COMPOSER) dump-autoload --optimize

# Access the application console
console:
	$(DOCKER_COMPOSE) exec app bash

# Connect to the database
db-connect:
	$(MYSQL)

# View logs
logs:
	$(DOCKER_COMPOSE) logs -f app

logs-db:
	$(DOCKER_COMPOSE) logs -f db

create-env:
	if [ ! -f .env ]; then cp .env.example .env; fi
