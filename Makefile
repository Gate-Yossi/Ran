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

# MariaDBにログイン
.PHONY: loginMariadb
loginMariadb:
	cd $(WOKR_DIR) \
	&& docker compose exec mariadb bash

# help
.PHONY: help
help:
	@grep -B 2 -E '^[a-zA-Z_-]+:' Makefile \
	| grep -v '.PHONY' \
	| grep -v -E '^\s*$$' \
	| tr '\n' ',' \
	| sed 's/--,/\n/g' \
	| awk -F, '{printf "%-20s %s\n", $$2, $$1}'
