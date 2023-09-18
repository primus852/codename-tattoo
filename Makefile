MAKEFLAGS += --warn-undefined-variables
SHELL := bash
.SHELLFLAGS := -eu -o pipefail -c

DOCKER := docker
DOCKER_COMPOSE := docker compose -f docker-compose.yml -f docker-compose.override.yml
ECHO := echo
CP := cp
EGREP := egrep

include .env

export

export PUID ?= $(shell id -u)
export PGID ?= $(shell id -g)

## This will provide one Make target per docker-compose config --services found
SERVICES := $(shell COMPOSE_FILE=$(COMPOSE_FILE) $(DOCKER_COMPOSE) config --services 2>/dev/null | xargs)
APP_SERVICES := $(filter app%, $(SERVICES))

CI_SERVICES := $(shell $(DOCKER_COMPOSE_CI) config --services 2>/dev/null | xargs)
CI_APP_SERVICES := $(filter app%, $(CI_SERVICES))

help:
	@$(ECHO) -e "\e[32m Usage: make [target] "
	@$(ECHO)
	@$(ECHO) -e "\e[1m targets:\e[0m"
	@$(EGREP) '^(.+):*\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'
.PHONY: help

build: ## Builds the development service
	$(DOCKER_COMPOSE) build --progress plain
.PHONY: build

start: ## Starts the application for local development (detached)
	service apache2 stop && $(DOCKER_COMPOSE) up -d --remove-orphans
.PHONY: start

restart: ## Restart the application for local development
	$(DOCKER_COMPOSE) restart
.PHONY: restart

stop: ## Stops all started services
	$(DOCKER_COMPOSE) stop
.PHONY: stop

down: ## Stops all started services
	$(DOCKER_COMPOSE) down
.PHONY: down

load-fixtures: ## Load all Fixtures locally
	$(DOCKER_COMPOSE) exec php bin/console doctrine:fixtures:load
.PHONY: load-fixtures

load-fixtures-users: ## Load Users Fixtures locally
	$(DOCKER_COMPOSE) exec php bin/console doctrine:fixtures:load --group=users
.PHONY: load-fixtures-users

load-fixtures-clients: ## Load Clients Fixtures locally
	$(DOCKER_COMPOSE) exec php bin/console doctrine:fixtures:load --group=clients
.PHONY: load-fixtures-clients

create-jwt: ## Create a JWT Key
	$(DOCKER_COMPOSE) exec php JWT_PASSPHRASE= JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem bin/console lexik:jwt:generate-keypair
.PHONY: create-jwt

jwt: ## Create a JWT Key
	$(DOCKER_COMPOSE) exec php bin/console lexik:jwt:generate-keypair
.PHONY: jwt

jwt-overwrite: ## Create a JWT Key
	$(DOCKER_COMPOSE) exec php bin/console lexik:jwt:generate-keypair --overwrite
.PHONY: jwt-overwrite

go-in: ## Create a JWT Key
	$(DOCKER_COMPOSE) exec php sh
.PHONY: go-in
