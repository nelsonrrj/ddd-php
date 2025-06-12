# Determinar qué archivo .env usar (por defecto .env)
ENV_FILE ?= .env
NO_ENV_COMMANDS := create-env setup install
ENV_REQUIRED := $(if $(filter-out $(NO_ENV_COMMANDS),$(MAKECMDGOALS)),true)

# Cargar variables del archivo de entorno. Fallar si .env no existe y es requerido.
ifneq (,$(wildcard $(ENV_FILE)))
    include $(ENV_FILE)
    export
else
    ifeq ($(ENV_REQUIRED),true)
        $(error El archivo .env no se encontró. Por favor, ejecuta 'make create-env' o 'make setup' para empezar.)
    endif
endif

# Variables
DOCKER_COMPOSE = docker-compose

# Add a new variable for root MySQL connection
MYSQL_ROOT = $(DOCKER_COMPOSE) exec $(DB_HOST) mysql -uroot -p$(DB_PASSWORD)

# Comandos usando las variables del .env
PHP = $(DOCKER_COMPOSE) exec app php
COMPOSER = $(DOCKER_COMPOSE) exec app composer
MYSQL = $(DOCKER_COMPOSE) exec $(DB_HOST) mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) $(DB_DATABASE)

# Colors for output
GREEN = \033[0;32m
YELLOW = \033[0;33m
RED = \033[0;31m
NC = \033[0m # No Color

# =============================================================================
# HELP
# =============================================================================
.PHONY: help help-section
help: ## Show this help menu
	@echo "$(GREEN)Available commands:$(NC)"
	@echo ""
	@$(MAKE) --no-print-directory help-section SECTION="Development"
	@$(MAKE) --no-print-directory help-section SECTION="Docker"
	@$(MAKE) --no-print-directory help-section SECTION="Database"
	@$(MAKE) --no-print-directory help-section SECTION="Testing"
	@$(MAKE) --no-print-directory help-section SECTION="Code Quality"
	@$(MAKE) --no-print-directory help-section SECTION="Utility"

help-section:
	@echo "$(YELLOW)$(SECTION):$(NC)"
	@awk '/^[a-zA-Z_-]+:.*## $(SECTION)/ { \
		split($$0, parts, ":"); \
		split($$0, desc, "## "); \
		gsub(/^$(SECTION): /, "", desc[2]); \
		printf "  \033[0;32m%-20s\033[0m %s\n", parts[1], desc[2] \
	}' $(MAKEFILE_LIST) | sort
	@echo ""

# =============================================================================
# DOCKER COMMANDS
# =============================================================================
.PHONY: up down build restart clean console

up: ## Docker: Start development environment
	@echo "$(GREEN)Starting development environment...$(NC)"
	$(DOCKER_COMPOSE) up -d --build

down: ## Docker: Stop and remove containers
	@echo "$(YELLOW)Stopping application...$(NC)"
	$(DOCKER_COMPOSE) down

build: ## Docker: Rebuild images without cache
	@echo "$(GREEN)Rebuilding images...$(NC)"
	$(DOCKER_COMPOSE) build --no-cache

restart: ## Docker: Restart the application
	@echo "$(YELLOW)Restarting application...$(NC)"
	$(MAKE) down
	$(MAKE) up

clean: ## Docker: Clean up containers, volumes, and images
	@echo "$(RED)Cleaning up Docker resources...$(NC)"
	$(DOCKER_COMPOSE) down -v --rmi all --remove-orphans

console: ## Docker: Access the application container shell
	$(DOCKER_COMPOSE) exec app bash

# =============================================================================
# DEVELOPMENT COMMANDS
# =============================================================================
.PHONY: install dev setup composer-install composer-update dump create-env

install: setup ## Development: Full installation (first time setup)

setup: ## Development: Setup project (create env, start containers, install dependencies)
	@echo "$(GREEN)Setting up project...$(NC)"
	$(MAKE) create-env
	$(MAKE) up
	$(MAKE) composer-install
	$(MAKE) migrate
	@echo "$(GREEN)Setup complete!$(NC)"

dev: up ## Development: Start development environment

composer-install: ## Development: Install composer dependencies
	@echo "$(GREEN)Installing dependencies...$(NC)"
	$(COMPOSER) install --optimize-autoloader

composer-update: ## Development: Update composer dependencies
	@echo "$(YELLOW)Updating dependencies...$(NC)"
	$(COMPOSER) update

dump: ## Development: Update autoload files
	$(COMPOSER) dump-autoload --optimize

create-env: ## Development: Create .env file from example
	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		echo "$(GREEN).env file created$(NC)"; \
	else \
		echo "$(YELLOW).env file already exists$(NC)"; \
	fi

# =============================================================================
# DATABASE COMMANDS
# =============================================================================
.PHONY: migrate db-reset db-connect db-backup db-restore

migrate: ## Database: Run migrations for current environment
	@echo "$(GREEN)Running migrations for $(ENV_FILE) ($(DB_DATABASE))...$(NC)"
	$(MAKE) wait-for-db
	$(PHP) src/Infrastructure/Persistence/command.php orm:schema-tool:create

db-reset: ## Database: Reset current environment database
	@echo "$(RED)Resetting $(DB_DATABASE)...$(NC)"
	$(MAKE) wait-for-db
	$(MYSQL_ROOT) -e "DROP DATABASE IF EXISTS $(DB_DATABASE); CREATE DATABASE $(DB_DATABASE);"
	$(MAKE) migrate
	@echo "$(GREEN)Database $(DB_DATABASE) reset!$(NC)"

db-connect: ## Database: Connect to current environment database
	@echo "$(GREEN)Connecting to $(DB_DATABASE)...$(NC)"
	$(MYSQL)

db-backup: ## Database: Create backup of current environment database
	@echo "$(GREEN)Creating backup of $(DB_DATABASE)...$(NC)"
	$(DOCKER_COMPOSE) exec $(DB_HOST) mysqldump -u$(DB_USERNAME) -p$(DB_PASSWORD) $(DB_DATABASE) > backup_$(DB_DATABASE)_$(shell date +%Y%m%d_%H%M%S).sql
	@echo "$(GREEN)Backup created successfully!$(NC)"

db-restore: ## Database: Restore database from backup (requires BACKUP_FILE variable)
	@if [ -z "$(BACKUP_FILE)" ]; then \
		echo "$(RED)Error: Please specify BACKUP_FILE variable$(NC)"; \
		echo "Example: make db-restore BACKUP_FILE=backup_my_database_20231201_120000.sql"; \
		exit 1; \
	fi
	@echo "$(YELLOW)Restoring $(DB_DATABASE) from $(BACKUP_FILE)...$(NC)"
	$(MYSQL) < $(BACKUP_FILE)
	@echo "$(GREEN)Database restored successfully!$(NC)"

# =============================================================================
# TESTING COMMANDS
# =============================================================================
.PHONY: test-all test-unit test-integration

test-all: ## Testing: Run all tests
	@echo "$(GREEN)Running all tests...$(NC)"
	$(PHP) vendor/bin/phpunit
	@echo "$(GREEN)All tests completed!$(NC)"

test-unit: ## Testing: Run unit tests
	@echo "$(GREEN)Running unit tests...$(NC)"
	$(PHP) vendor/bin/phpunit tests/Unit

test-integration: ## Testing: Run integration tests
	@echo "$(GREEN)Running integration tests...$(NC)"
	$(PHP) vendor/bin/phpunit tests/Integration
	@echo "$(GREEN)Integration tests completed!$(NC)"\n"

# =============================================================================
# CODE QUALITY COMMANDS
# =============================================================================
.PHONY: syntax-check fix format validate

syntax-check: ## Code Quality: Check PHP syntax errors
	@echo "$(GREEN)Checking PHP syntax...$(NC)"
	@find src tests -name "*.php" -print0 | xargs -0 -I {} $(PHP) -l {} | grep -v "No syntax errors detected" || echo "$(GREEN)No syntax errors found!$(NC)"

fix: ## Code Quality: Fix code style issues automatically
	@echo "$(GREEN)Fixing code style issues...$(NC)"
	$(PHP) vendor/bin/php-cs-fixer fix src/
	$(PHP) vendor/bin/php-cs-fixer fix tests/

format: fix ## Code Quality: Alias for fix command

validate: ## Code Quality: Validate composer.json and lock files
	@echo "$(GREEN)Validating composer files...$(NC)"
	$(COMPOSER) validate --strict

# =============================================================================
# UTILITY COMMANDS
# =============================================================================
.PHONY: status info clear-cache env-info wait-for-db

status: ## Utility: Show application status
	@echo "$(GREEN)Application Status:$(NC)"
	@echo "Containers:"
	$(DOCKER_COMPOSE) ps
	@echo ""
	@echo "Database connection:"
	@$(MYSQL) -e "SELECT 'Database connected successfully!' as status;" 2>/dev/null || echo "$(RED)Database connection failed$(NC)"

info: ## Utility: Show system information
	@echo "$(GREEN)System Information:$(NC)"
	@echo "Docker Compose version:"
	$(DOCKER_COMPOSE) --version
	@echo ""
	@echo "PHP version:"
	$(PHP) --version
	@echo ""
	@echo "Composer version:"
	$(COMPOSER) --version

clear-cache: ## Utility: Clear application cache (if applicable)
	@echo "$(GREEN)Clearing cache...$(NC)"
	$(PHP) -r "if(function_exists('opcache_reset')) opcache_reset();"
	@echo "$(GREEN)Cache cleared!$(NC)"

env-info: ## Utility: Show current environment information
	@echo "$(GREEN)Current Environment: $(ENV_FILE)$(NC)"
	@echo "Database: $(DB_DATABASE)"
	@echo "Host: $(DB_HOST)"
	@echo "Username: $(DB_USERNAME)"

wait-for-db: ## Utility: Waits for the database to be ready to accept connections
	@echo "Waiting for database to be ready..."
	@n=0; \
	while ! $(DOCKER_COMPOSE) exec -T $(DB_HOST) mysqladmin ping -h"localhost" -u"root" -p"$(DB_PASSWORD)" --silent; do \
		n=$$(($$n+1)); \
		if [ $$n -gt 10 ]; then \
			echo "$(RED)Database connection timed out after 20 seconds.$(NC)"; \
			exit 1; \
		fi; \
		echo "Database is unavailable - sleeping for 2s (attempt $$n)..."; \
		sleep 2; \
	done
	@echo "$(GREEN)Database is up and running!$(NC)"

# Default target
.DEFAULT_GOAL := help
