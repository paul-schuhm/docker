config:
	@echo "Config prod"
	docker compose -f compose.yaml -f compose.prod.yaml config
	@echo "Config dev"
	docker compose -f compose.yaml -f compose.dev.yaml config

up-prod:
	docker compose -f compose.yaml -f compose.prod.yaml up

up-dev:
	docker compose -f compose.yaml -f compose.dev.yaml up

build:
	docker compose -f compose.yaml -f compose.dev.yaml build web

down-dev:
	docker compose -f compose.yaml -f compose.dev.yaml up -d

restart: down-dev up-dev

