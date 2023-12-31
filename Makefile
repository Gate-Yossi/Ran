SHELL = /bin/bash
WOKR_DIR = ./app
.DEFAULT_GOAL := help

# アプリのセットアップ
.PHONY: setup
setup:
	@make composer_install
	@make build

# コンテナビルド 
.PHONY: build
build:
	cd $(WOKR_DIR) \
	&& docker compose build

# composer install
.PHONY: composer_install
composer_install:
	cd $(WOKR_DIR) \
	&& docker compose run -it --rm composer-cmd install

# アプリの起動
.PHONY: up
up:
	cd $(WOKR_DIR) \
	&& docker compose up -d

# アプリの停止
.PHONY: down
down:
	cd $(WOKR_DIR) \
	&& docker compose down

# コンテナの状態確認
.PHONY: ps
ps:
	cd $(WOKR_DIR) \
	&& docker compose ps

# コンテナの削除
.PHONY: clean
clean:
	cd $(WOKR_DIR) \
	&& docker-compose down --rmi all --volumes --remove-orphans

# MariaDBにログイン
.PHONY: login_mariadb
login_mariadb:
	cd $(WOKR_DIR) \
	&& docker compose exec mariadb bash

# MariaDB(READ)にログイン
.PHONY: login_mariadb
login_mariadb_read:
	cd $(WOKR_DIR) \
	&& docker compose exec mariadb_read bash

# migrate create ex) SQL_NAME=xxx make migrate_create
.PHONY: migrate_create
migrate_create:
	cd $(WOKR_DIR) \
	&& docker compose run -it --rm migrate-cmd create -ext sql -dir /migrations -seq $(SQL_NAME)

# migrate version
.PHONY: migrate_version
migrate_version:
	cd $(WOKR_DIR) \
	&& . .env \
	&& docker compose run -it --rm migrate-cmd -path /migrations -database "$${MIGRATE_DNS}" version

# migrate up
.PHONY: migrate_up
migrate_up:
	cd $(WOKR_DIR) \
	&& . .env \
	&& docker compose run -it --rm migrate-cmd -path /migrations -database "$${MIGRATE_DNS}" up

# migrate down
.PHONY: migrate_down
migrate_down:
	cd $(WOKR_DIR) \
	&& . .env \
	&& docker compose run -it --rm migrate-cmd -path /migrations -database "$${MIGRATE_DNS}" down

# migrate force
.PHONY: migrate_force
migrate_force:
	cd $(WOKR_DIR) \
	&& . .env \
	&& docker compose run -it --rm migrate-cmd -path /migrations -database "$${MIGRATE_DNS}" force 1

# PHP-FPMにログイン
.PHONY: login_php_fpm
login_php_fpm:
	cd $(WOKR_DIR) \
	&& docker compose exec php-fpm bash

# nginx
.PHONY: login_nginx
login_nginx:
	cd $(WOKR_DIR) \
	&& docker compose exec nginx sh

# log
.PHONY: logs
logs:
	cd $(WOKR_DIR) \
	&& docker compose logs

# localhostへのリクエスト
.PHONY: test_req
test_req:
	curl -k https://localhost

# help
.PHONY: help
help:
	@grep -B 2 -E '^[a-zA-Z_-]+:' Makefile \
	| grep -v '.PHONY' \
	| grep -v -E '^\s*$$' \
	| tr '\n' ',' \
	| sed 's/--,/\n/g' \
	| awk -F, '{printf "%-20s %s\n", $$2, $$1}'
