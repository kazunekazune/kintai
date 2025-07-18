# CoachTech 勤怠管理アプリ

## 概要

ある企業が開発した独自の勤怠管理アプリです。ユーザーの勤怠と管理を目的としています。

## 技術スタック

-   **PHP**: 8.4
-   **Laravel**: 12.x
-   **MySQL**: 8.0
-   **Docker**: Laravel Sail
-   **認証**: Laravel Fortify

## 環境構築

### 前提条件

-   Docker Desktop
-   Git

### セットアップ手順

1. **リポジトリのクローン**

    ```bash
    git clone <repository-url>
    cd kintai-app
    ```

2. **Docker コンテナの起動**

    ```bash
    ./vendor/bin/sail up -d
    ```

3. **依存関係のインストール**

    ```bash
    ./vendor/bin/sail composer install
    ```

4. **環境設定ファイルの作成**

    ```bash
    cp .env.example .env
    ```

5. **アプリケーションキーの生成**

    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

6. **データベースのマイグレーション**

    ```bash
    ./vendor/bin/sail artisan migrate
    ```

7. **ダミーデータの作成**
    ```bash
    ./vendor/bin/sail artisan db:seed
    ```

## ログイン情報

### 一般ユーザー

-   **メールアドレス**: user@example.com
-   **パスワード**: password

### 管理者ユーザー

-   **メールアドレス**: admin@example.com
-   **パスワード**: password

## 機能一覧

### 一般ユーザー機能

-   勤怠打刻（出勤・退勤・休憩）
-   勤怠一覧表示
-   勤怠詳細表示・修正申請
-   修正申請一覧

### 管理者機能

-   全ユーザーの勤怠一覧表示
-   勤怠詳細表示・修正
-   スタッフ一覧表示
-   スタッフ別勤怠一覧
-   修正申請承認

## 画面一覧

| 画面 ID | 画面名称                         | パス                                   | 説明                 |
| ------- | -------------------------------- | -------------------------------------- | -------------------- |
| PG01    | 会員登録画面（一般ユーザー）     | /register                              | 新規ユーザー登録     |
| PG02    | ログイン画面（一般ユーザー）     | /login                                 | 一般ユーザーログイン |
| PG03    | 勤怠登録画面（一般ユーザー）     | /attendance                            | 勤怠打刻画面         |
| PG04    | 勤怠一覧画面（一般ユーザー）     | /attendance/list                       | 月別勤怠一覧         |
| PG05    | 勤怠詳細画面（一般ユーザー）     | /attendance/{id}                       | 勤怠詳細・修正申請   |
| PG06    | 申請一覧画面（一般ユーザー）     | /stamp_correction_request/list         | 修正申請一覧         |
| PG07    | ログイン画面（管理者）           | /admin/login                           | 管理者ログイン       |
| PG08    | 勤怠一覧画面（管理者）           | /admin/attendance/list                 | 管理者用勤怠一覧     |
| PG09    | 勤怠詳細画面（管理者）           | /attendance/{id}                       | 管理者用勤怠詳細     |
| PG10    | スタッフ一覧画面（管理者）       | /admin/staff/list                      | スタッフ一覧         |
| PG11    | スタッフ別勤怠一覧画面（管理者） | /admin/attendance/staff/{id}           | スタッフ別勤怠一覧   |
| PG12    | 申請一覧画面（管理者）           | /stamp_correction_request/list         | 管理者用申請一覧     |
| PG13    | 修正申請承認画面（管理者）       | /stamp_correction_request/approve/{id} | 修正申請承認         |

## データベース設計

### テーブル構成

-   **users**: ユーザー情報
-   **attendances**: 勤怠記録
-   **breaks**: 休憩記録
-   **attendance_correction_requests**: 修正申請

### ER 図

```
users (1) ----< attendances (1) ----< breaks
users (1) ----< attendance_correction_requests
users (1) ----< attendance_correction_requests (approved_by)
```

## 開発・テスト

### 開発サーバーの起動

```bash
./vendor/bin/sail up -d
```

### テストの実行

```bash
./vendor/bin/sail artisan test
```

### コード品質チェック

```bash
./vendor/bin/sail artisan pint
```

## ライセンス

このプロジェクトは企業内での使用を目的としています。

## 作者

CoachTech 開発チーム
