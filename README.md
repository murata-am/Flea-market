# フリマアプリ

## プロジェクトの概要
- サービス名 : coachtechフリマ
- サービス概要 : ある企業が開発した独自のフリマアプリ

## 環境構築手順

Docker のビルド
1. git clone git@github.com:murata-am/Flea-market.git  
- Flea-marketへ移動する
2. docker-compose up -d --build  

※MacのPCの場合、no matching manifest for linux/arm64/v8 in the manifest list entriesのメッセージが表示されビルドができないことがあります。
エラーが発生する場合は、docker-compose.ymlファイルの「mysql」内に「platform」の項目を追加で記載してください
```bash
mysql:
    platform: linux/x86_64(この文追加)
    image: mysql:8.0.26
    environment:
```

Laravel 環境構築

3. docker-compose exec php bash    
4. composer install    
5. cp .env.example .env   

   .envファイルを作成後、以下の環境変数を変更する

```bash
 DB_CONNECTION=mysql　　
 DB_HOST=mysql　　
 DB_PORT=3306　　
 DB_DATABASE=laravel_db　　
 DB_USERNAME=laravel_user　　
 DB_PASSWORD=laravel_pass
```

6. アプリケーションキーの作成
    php artisan key:generate
7. シンボリックリンクの作成
    php artisan storage:link
8. マイグレーションの実行
    php artisan migrate
9. シーディングの実行
    php artisan db:seed

※環境構築の動作確認時にファイルの権限のエラーが起きることがありましたので、その際は次のコマンドなどで対応いただけたら幸いです
```bash
sudo chmod -R 777 src/*
```
## テストユーザーのログイン情報

- メールアドレス:testuser@example.com
- パスワード:password

## ER 図

![alt text](er.png)

## 開発環境

- PHP 7.4.9
- Laravel 8.83.8
- MySQL 8.0.26

## URL

- 開発環境 http://localhost/
- ログイン画面 http://localhost/login
- phpMyAdmin http://localhost:8080
