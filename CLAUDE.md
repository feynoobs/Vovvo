# CLAUDE.md

このファイルは、このリポジトリでコードを操作する際に Claude Code (claude.ai/code) に指針を提供します。

## プロジェクト

Vovvo は Laravel 12 + React 学習用スカッフォルドです。
2chライクなBBSを作りたいです。
カスタムビジネスロジックはまだなく、デフォルトの User モデル、認証インフラストラクチャ、およびシングルウェルカムルートのみが存在する新規プロジェクトです。

## コマンド

```bash
# フロントエンドのみ
./vendor/bin/sail npm run dev           # Vite HMR ポート 5173
./vendor/bin/sail npm run build         # 本番バンドル

# テスト
./vendor/bin/sail artisan test --filter=TestName   # 名前またはクラスでシングルテストを実行

# データベース
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan migrate --seed

# コード生成
./vendor/bin/sail artisan make:model Post --migration
./vendor/bin/sail artisan make:controller PostController --resource
./vendor/bin/sail artisan make:test Feature/PostTest

# Docker (Laravel Sail)
./vendor/bin/sail up -d
./vendor/bin/sail artisan <command>
```

## アーキテクチャ

**リクエストフロー:** ルート (`routes/web.php` または `routes/api.php`) → コントローラ → モデル / サービス → レスポンス

**レイヤー規約:**
- `app/Models/` — Eloquent モデル (リレーションシップ、スコープ、型キャスト)
- `app/Http/Controllers/` — シンリクエストハンドラー、複雑なロジックはサービスに委譲
- `app/Services/` — コントローラが大きくなった時、ビジネスロジックをここに抽出
- `app/Http/Middleware/` — 横断的処理
- `app/Providers/AppServiceProvider` — サービスコンテナバインディングとブートロジック

**テスト分割:**
- `tests/Unit/` — `PHPUnit\Framework\TestCase` を拡張、フレームワークブートなし
- `tests/Feature/` — `Tests\TestCase` を拡張、完全な HTTP/DB 統合

PHPUnit は `phpunit.xml` で設定され、インメモリの `testing` データベース、`array` キャッシュ/セッションドライバ、`sync` キューを使用するため、テストは副作用なく実行されます。

## フロントエンド

アセットは Vite で管理されます。エントリーポイント: `resources/css/app.css` (Tailwind v4) および `resources/ts/app.ts` (CSRF ヘッダー事前設定済み Axios)。

Blade テンプレートでコンパイル済みアセットを読み込む場合は `@vite(['resources/css/app.css', 'resources/js/app.ts'])` を使用します。

React を追加する場合:
```bash
sail npm install react react-dom @vitejs/plugin-react
```
その後、`vite.config.ts` に React プラグインを追加し、`resources/ts/components/` の下に `.tsx` コンポーネントを作成します。

## 主要な規約

**PHP:** 明示的な戻り値型、強力なジェネリクス (`array<string, string>`)、PSR-4 名前空間。`protected function casts(): array` 構文を使用 (`$casts` プロパティではなく)。

**データベース命名:** テーブルは小文字複数形 (`posts`)、カラムはスネークケース (`created_at`)、外部キーは `{単数形}_id` (`user_id`)。

**インポート:** 特定のファサードインポートを使用 (`use Illuminate\Support\Facades\DB;`); 生クエリより Eloquent を推奨。

**バリデーション:** コントローラで `$request->validate([...])` を使用 — Laravel は自動的に `ValidationException` をスロー。

**CSRF:** すべての Blade フォームに `@csrf` を含める。JS リクエストは `bootstrap.js` で既に設定済みの Axios `X-CSRF-TOKEN` ヘッダーを使用。

**環境:** 開発は MySQL を使用 (`DB_CONNECTION=mysql`)。Docker Sail は MySQL 8.4、Redis、Meilisearch、Mailpit、MinIO を提供し、本番のような環境を実現します。

**命名規則:** 
- ソースコードはキャメルケース
- データベースはスネークケース

**コメント:** 
`phpdoc`に準拠したコメントを書いてください

**テスト:**
エンドポイントごとにテストを作成する

## モデル
### 掲示板の一覧のモデル

**テーブルの名前:** groups

**カラム:**

|名前|型|インデックス|null|オートインクリメント|備考|
|:---|:---|:---|:---:|:---:|:---|
|id|unsigned bigint|PK|☓|◯||
|name|varchar(255)||☓|☓|グループの名称|
|sort|unsigned bigint||☓|☓|1から始まる掲示板の並び順。ユニークな値|
|deleted_at|timestamp||☓|☓|論理削除。値がnullではないときは削除された時間を記録する|
|created_at|timestamp||☓|☓|レコードが生成された日時|
|updated_at|timestamp||☓|☓|レコードが更新された日時|

### スレッドの一覧のモデル
**テーブルの名前:** boards

**カラム:**

|名前|型|インデックス|null|オートインクリメント|備考|
|:---|:---|:---|:---:|:---:|:---|
|id|unsigned bigint|PK|☓|◯||
|group_id|unsigned bigint|FK(groups.id)|☓|☓|外部キー制約|
|name|varchar(255)||☓|☓|掲示場の名前|
|sort|unsigned bigint||☓|☓|1から始まる掲示板の並び順。group_idごとにユニークな値|
|deleted_at|timestamp||◯|☓|論理削除。値がnullではないときは削除された時間を記録する|
|created_at|timestamp||☓|☓|レコードが生成された日時|
|updated_at|timestamp||☓|☓|レコードが更新された日時|

### レスの一覧のモデル
**テーブルの名前:** threads

**カラム:**

|名前|型|インデックス|null|オートインクリメント|備考|
|:---|:---|:---|:---:|:---:|:---|
|id|unsigned bigint|PK|☓|◯||
|board_id|unsigned bigint|FK(boards.id)|☓|☓|外部キー制約|
|name|varchar(255)||☓|☓|スレッドの名前|
|sort|unsigned bigint||☓|☓|1から始まる掲示板の並び順。group_idごとにユニークな値|
|deleted_at|timestamp||◯|☓|論理削除。値がnullではないときは削除された時間を記録する|
|created_at|timestamp||☓|☓|レコードが生成された日時|
|updated_at|timestamp||☓|☓|レコードが更新された日時|

### レスのモデル
**テーブルの名前:** responses

**カラム:**

|名前|型|インデックス|null|オートインクリメント|備考|
|:---|:---|:---|:---:|:---:|:---|
|id|unsigned bigint|PK|☓|◯||
|thread_id|unsigned bigint|FK(threads.id)|☓|☓|外部キー制約|
|content|text||☓|☓|スレッドの名前|
|sort|unsigned bigint||☓|☓|1から始まる掲示板の並び順。thread_idごとにユニークな値|
|deleted_at|timestamp||◯|☓|論理削除。値がnullではないときは削除された時間を記録する|
|created_at|timestamp||☓|☓|レコードが生成された日時|
|updated_at|timestamp||☓|☓|レコードが更新された日時|

### リレーション

- Group hasMany Board
- Board belongsTo Group
- Board hasMany Thread
- Thread belongsTo Board
- Thread hasMany Response
- Response belongsTo Thread

## エンドポイント

APIはすべてJSONでやり取りする。
|名前|URL|メソッド|パラメータ|
|:---|:---|:---:|:---|
|投稿API|/api/post|POST||
|掲示場一覧取得|/api/group|GET||
|スレッド一覧取得|/api/board/{id}|GET|掲示板のID|
|レス一覧取得|/api/thread/{id}|GET|スレッドのID|


## 実装完了条件

- 4テーブルすべてのマイグレーション作成
- モデルにリレーション定義
- APIコントローラ作成
- api.phpにルート定義
- 各エンドポイントに対応するFeatureテスト作成
- 途中で停止しないこと
