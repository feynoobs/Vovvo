# CLAUDE.md

このファイルは、このリポジトリでコードを操作する際に Claude Code (claude.ai/code) に指針を提供します。

## プロジェクト

Vovvo は Laravel 12 + React 学習用スカッフォルドです。カスタムビジネスロジックはまだなく、デフォルトの User モデル、認証インフラストラクチャ、およびシングルウェルカムルートのみが存在する新規プロジェクトです。

## コマンド

```bash
# 1回限りのセットアップ
composer setup        # 依存関係インストール、キー生成、マイグレーション、npm install、npm build

# 開発 (同時実行: Laravel server、queue listener、log viewer、Vite HMR)
composer dev

# フロントエンドのみ
npm run dev           # Vite HMR ポート 5173
npm run build         # 本番バンドル

# テスト
composer test         # config キャッシュをクリア、PHPUnit スイート全体を実行
php artisan test --filter=TestName   # 名前またはクラスでシングルテストを実行

# データベース
php artisan migrate
php artisan migrate --seed

# コード生成
php artisan make:model Post --migration
php artisan make:controller PostController --resource
php artisan make:test Feature/PostTest

# Docker (Laravel Sail)
./vendor/bin/sail up
./vendor/bin/sail artisan <command>
```

## アーキテクチャ

**リクエストフロー:** ルート (`routes/web.php` または `routes/api.php`) → コントローラ → モデル / サービス → レスポンス

**レイヤー規約:**
- `app/Models/` — Eloquent モデル (リレーションシップ、スコープ、型キャスト)
- `app/Http/Controllers/` — シンリクエストハンドラー、複雑なロジックはサービスに委譲
- `app/Services/` — コントローラが大きくなった時、ビジネスロジックをここに抽出
- `app/Http/Middleware/` — 横断的処理
- `app/Http/Resources/` — API レスポンスフォーマッティング (JSON API 構築時)
- `app/Providers/AppServiceProvider` — サービスコンテナバインディングとブートロジック

**テスト分割:**
- `tests/Unit/` — `PHPUnit\Framework\TestCase` を拡張、フレームワークブートなし
- `tests/Feature/` — `Tests\TestCase` を拡張、完全な HTTP/DB 統合

PHPUnit は `phpunit.xml` で設定され、インメモリの `testing` データベース、`array` キャッシュ/セッションドライバ、`sync` キューを使用するため、テストは副作用なく実行されます。

## フロントエンド

アセットは Vite で管理されます。エントリーポイント: `resources/css/app.css` (Tailwind v4) および `resources/js/app.js` (CSRF ヘッダー事前設定済み Axios)。

Blade テンプレートでコンパイル済みアセットを読み込む場合は `@vite(['resources/css/app.css', 'resources/js/app.js'])` を使用します。

React を追加する場合:
```bash
npm install react react-dom @vitejs/plugin-react
```
その後、`vite.config.js` に React プラグインを追加し、`resources/js/components/` の下に `.jsx` コンポーネントを作成します。

## 主要な規約

**PHP:** 明示的な戻り値型、強力なジェネリクス (`array<string, string>`)、PSR-4 名前空間。`protected function casts(): array` 構文を使用 (`$casts` プロパティではなく)。

**データベース命名:** テーブルは小文字複数形 (`posts`)、カラムはスネークケース (`created_at`)、外部キーは `{単数形}_id` (`user_id`)。

**インポート:** 特定のファサードインポートを使用 (`use Illuminate\Support\Facades\DB;`); 生クエリより Eloquent を推奨。

**バリデーション:** コントローラで `$request->validate([...])` を使用 — Laravel は自動的に `ValidationException` をスロー。

**CSRF:** すべての Blade フォームに `@csrf` を含める。JS リクエストは `bootstrap.js` で既に設定済みの Axios `X-CSRF-TOKEN` ヘッダーを使用。

**環境:** 開発は SQLite を使用 (`DB_CONNECTION=sqlite`)。Docker Sail は MySQL 8.4、Redis、Meilisearch、Mailpit、MinIO を提供し、本番のような環境を実現します。
