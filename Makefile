# --- Variables ---
MAKEFILE_PATH := $(realpath $(lastword $(MAKEFILE_LIST)))
MAKEFILE_DIR := $(dir $(MAKEFILE_PATH))
DOCKER_COMPOSE_FILE := $(MAKEFILE_DIR)compose.yml
DOCKER_COMPOSE_OVERRIDE_FILE := $(MAKEFILE_DIR)compose.override.yml
DOCKER_COMPOSE_DEV := docker compose --project-directory "$(MAKEFILE_DIR)" -f "$(DOCKER_COMPOSE_FILE)" -f "$(DOCKER_COMPOSE_OVERRIDE_FILE)"
FORWARD_ARG_TARGETS := dev build stop down test api-sh db-cli redis-cli
RAW_EXTRA_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
EXTRA_ARGS := $(filter-out --,$(RAW_EXTRA_ARGS))
HOST_DOCKER_USER := $(shell id -u):$(shell id -g)

# database
DB_USER := app_user
DB_NAME := app_db

# --- Configuration ---
.DEFAULT_GOAL := help

ENV_FILE ?= $(MAKEFILE_DIR).env
ifneq (,$(wildcard $(ENV_FILE)))
    include $(ENV_FILE)
    export
endif

ifneq ($(filter $(firstword $(MAKECMDGOALS)),$(FORWARD_ARG_TARGETS)),)
$(eval $(EXTRA_ARGS):;@:)
endif

# --- Guards ---
define confirm_action
	@echo "Are you sure? [y/N] " && read ans && [ $${ans:-N} = y ]
endef

.PHONY: help
help: ## Show this help message
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

# Runtime lifecycle
.PHONY: dev
dev: ## Start development environment
	$(DOCKER_COMPOSE_DEV) up -d $(EXTRA_ARGS)

.PHONY: build
build: ## Build or rebuild services
	$(DOCKER_COMPOSE_DEV) build $(EXTRA_ARGS)

.PHONY: stop
stop: ## Stop containers
	$(DOCKER_COMPOSE_DEV) stop $(EXTRA_ARGS)

.PHONY: down
down: ## Stop and remove containers and networks
	$(DOCKER_COMPOSE_DEV) down --remove-orphans $(EXTRA_ARGS)

.PHONY: test
test: ## Run API service PHPUnit tests
	$(DOCKER_COMPOSE_DEV) exec api sh -lc 'APP_ENV=test APP_DEBUG=0 php bin/console cache:clear --no-warmup >/dev/null && APP_ENV=test APP_DEBUG=0 vendor/bin/phpunit $(EXTRA_ARGS)'

.PHONY: api-sh
api-sh: ## Open API container sh
	$(DOCKER_COMPOSE_DEV) exec api sh $(EXTRA_ARGS)

.PHONY: db-cli
db-cli: ## Open Database cli
	$(DOCKER_COMPOSE_DEV) exec database psql -U $(DB_USER) -d $(DB_NAME) $(EXTRA_ARGS)

.PHONY: redis-cli
redis-cli: ## Open Redis cli
	$(DOCKER_COMPOSE_DEV) exec redis redis-cli $(EXTRA_ARGS)
