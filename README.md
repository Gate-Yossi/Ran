# Ran
====

読み方:ラーン

## Description
[よっしーノート](https://yossi-note.com/)のDockerとslimで作る開発環境関連のソースを管理しているリポジトリです。

## Usage

```bash
# リポジトリのクローン
git clone git@github.com:Gate-Yossi/Ran.git
cd Ran
```

### アプリのセットアップから起動

クローン後に初回起動させる場合は、下記のコマンドで起動させます。

```bash
make setup
make up
```

### コンテナの内容を修正した場合

コンテナの内容を修正した場合は、下記のコマンドで反映させます。

```bash
make build
```

### アプリの停止

アプリを停止させる場合は、下記のコマンドで停止させます。

```bash
make down
```

### MariaDBへのログイン

MariaDBへのログイン

```bash
make login_mariadb
```

### Redis Commanderへのログイン

下記のURLにアクセス

http://localhost:8081/


### ヘルプ

```bash
make help
```
