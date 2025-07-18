# CoachTech 勤怠管理アプリ

## 環境構築

### Docker ビルド

1. `git clone https://github.com/kazunekazune/kintai.git`
2. `cd kintai-app`
3. `./vendor/bin/sail up -d --build`

**注意**: MySQL は、OS によって起動しない場合があるのでそれぞれの PC に合わせて `docker-compose.yml` ファイルを編集してください。

### Laravel 環境構築

1. `./vendor/bin/sail exec laravel.test bash`
2. `composer install`
3. `.env.example`ファイルから`.env`を作成し、環境を全更新
4. `php artisan key:generate`
5. `php artisan migrate`
6. `php artisan db:seed --class=AdminUserSeeder`

## 使用技術（実行環境）

-   **PHP**: 8.2
-   **Laravel**: 11.x
-   **MySQL**: 8.0
-   **Docker**: Laravel Sail
-   **認証**: Laravel Fortify
-   **UI**: Bootstrap 5
-   **言語**: 日本語対応

## ER 図

<--- 作成した ER 図の画像 --->

**ER 図の確認**: `http://localhost/er-diagram.html` で確認可能

## URL

-   **開発環境**: `http://localhost/`
-   **phpMyAdmin**: `http://localhost:8080/`

## 機能一覧

### 一般ユーザー機能

-   **ユーザー登録・ログイン**: 新規登録とログイン機能
-   **勤怠打刻**: 出勤・退勤・休憩開始・休憩終了
-   **勤怠一覧**: 自分の勤怠記録一覧表示
-   **勤怠詳細**: 勤怠詳細表示と修正申請
-   **修正申請**: 勤怠修正申請の作成と一覧表示
-   **メール認証**: メールアドレス認証機能

### 管理者機能

-   **全ユーザー勤怠一覧**: 全スタッフの勤怠記録一覧
-   **勤怠詳細・修正**: スタッフの勤怠詳細表示と修正
-   **スタッフ一覧**: 登録ユーザー一覧表示
-   **スタッフ別勤怠**: 特定スタッフの勤怠一覧
-   **修正申請承認**: 修正申請の承認・却下処理

## テストユーザー

### 一般ユーザー

-   **メールアドレス**: user@example.com
-   **パスワード**: password
-   **登録方法**: `/register` から新規登録

### 管理者ユーザー

-   **メールアドレス**: admin@example.com
-   **パスワード**: password
-   **アクセス**: `/admin/login` からログイン

## 画面一覧

| 画面 ID | 画面名称                         | パス                                   | 説明                   |
| ------- | -------------------------------- | -------------------------------------- | ---------------------- |
| PG01    | 会員登録画面（一般ユーザー）     | /register                              | 新規ユーザー登録       |
| PG02    | ログイン画面（一般ユーザー）     | /login                                 | 一般ユーザーログイン   |
| PG03    | 勤怠打刻画面（一般ユーザー）     | /attendance                            | 出勤・退勤・休憩打刻   |
| PG04    | 勤怠一覧画面（一般ユーザー）     | /attendance/list                       | 自分の勤怠一覧         |
| PG05    | 勤怠詳細画面（一般ユーザー）     | /attendance/{id}                       | 勤怠詳細・修正申請     |
| PG06    | 申請一覧画面（一般ユーザー）     | /stamp_correction_request/list         | 修正申請一覧           |
| PG07    | ログイン画面（管理者）           | /admin/login                           | 管理者ログイン         |
| PG08    | 勤怠一覧画面（管理者）           | /admin/attendance/list                 | 全スタッフ勤怠一覧     |
| PG09    | 勤怠詳細画面（管理者）           | /admin/attendance/{id}                 | スタッフ勤怠詳細・修正 |
| PG10    | スタッフ一覧画面（管理者）       | /admin/staff/list                      | スタッフ一覧           |
| PG11    | スタッフ別勤怠一覧画面（管理者） | /admin/attendance/staff/{id}           | 特定スタッフ勤怠一覧   |
| PG12    | 申請一覧画面（管理者）           | /admin/correction-request/list         | 修正申請一覧           |
| PG13    | 修正申請承認画面（管理者）       | /admin/correction-request/approve/{id} | 修正申請承認           |

## データベース設計

### テーブル構成（10 個）

| No. | テーブル名                     | 説明                         |
| --- | ------------------------------ | ---------------------------- |
| 1   | users                          | ユーザー情報（一般・管理者） |
| 2   | attendances                    | 勤怠記録（出勤・退勤・休憩） |
| 3   | break_records                  | 休憩記録（休憩開始・終了）   |
| 4   | attendance_correction_requests | 勤怠修正申請                 |
| 5   | password_reset_tokens          | パスワードリセット           |
| 6   | sessions                       | セッション管理               |
| 7   | cache                          | キャッシュ                   |
| 8   | jobs                           | ジョブキュー                 |
| 9   | failed_jobs                    | 失敗したジョブ               |
| 10  | personal_access_tokens         | API トークン                 |

## 開発・テスト

### 開発サーバーの起動

```bash
./vendor/bin/sail up -d
```

### テストの実行

```bash
./vendor/bin/sail artisan test
```

### データベースリセット

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

## セキュリティ

### 保護されているファイル

-   `.env` - 環境変数（アプリケーションキー、データベースパスワード等）
-   `storage/*.key` - 暗号化キー
-   `*.pem`, `*.key`, `*.crt` - 証明書・秘密鍵
-   `storage/logs/` - ログファイル

## ライセンス

このプロジェクトは企業内での使用を目的としています。

## 作者

CoachTech 開発チーム

---

**最終更新**: 2024 年 7 月 18 日
**バージョン**: 1.0.0
