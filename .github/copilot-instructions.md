# プロジェクトガイドライン – Vovvo (Laravel 12 + React学習用)

## コードスタイル

**PHP (8.2+)**: 明示的な戻り値型、強い型付け、PSR-4 名前空間を使用する。
- ✅ `protected function casts(): array { ... }`
- ✅ ジェネリクス付きの型ヒント: `array<string, string>`, `list<string>`
- ✅ ジェネリクス用の最新PHPDoc `@use` ([User.php](../app/Models/User.php) を参照)

**フォーマット**: PSR-2 スタイル、4スペース インデント。Blade テンプレートは `{{ }}` で出力。

**Laravel慣例**:
- モデル: `app/Models/` (Eloquent ORM、テスト用に `HasFactory` トレイトを使用)
- コントローラ: `app/Http/Controllers/` (基本 `Controller` クラスから継承)
- ルート: `routes/web.php` (HTTP)、`routes/api.php` (将来のAPI)
- サービスプロバイダ: `app/Providers/` で依存関係を登録

**データベース命名規則**:
- テーブル: 小文字複数形 (`users`, `posts`, `comments`)
- カラム: スネークケース (`email_verified_at`, `user_id`, `remember_token`)
- 外部キー: `{テーブル単数形}_id` (例: `user_id` は `users` テーブルを参照)

**テスト**:
- ユニットテスト: `tests/Unit/` (独立したロジック、`PHPUnit\Framework\TestCase` を拡張)
- フィーチャテスト: `tests/Feature/` (HTTP/統合テスト、`Tests\TestCase` を拡張)
- Laravelファクトリを使用 ([UserFactory.php](../database/factories/UserFactory.php) を参照) してテストデータをシード

## アーキテクチャ

**現在の状態**: 新規 Laravel 12 スカッフォルド。サービスレイヤーなし。Eloquent モデルがデータを処理。

**推奨パターン**:
1. **モデル** (`app/Models/`): データベース関係とスコープを定義
2. **コントローラ** (`app/Http/Controllers/`): リクエスト処理、サービスまたはモデルに委譲
3. **サービス** (`app/Services/` 必要に応じて): 複雑なビジネスロジックを抽出
4. **ミドルウェア** (`app/Http/Middleware/` 必要に応じて): 横断的処理
5. **リソース** (`app/Http/Resources/` API用): モデル応答のフォーマット

**データフロー**:
- リクエスト → ルート (`routes/web.php`) → コントローラ → モデル/サービス → レスポンス

**状態管理**: 
- バックエンド: データベース経由のセッション ([.env](../.env) で設定: `SESSION_DRIVER=database`)
- フロントエンド: Vite + Tailwind、JS フレームワークなし (React/Vue統合に備える)

## ビルドとテスト

**セットアップ**:
```bash
composer setup     # 1回限り: install、key生成、migrate、npm install、build
```

**開発**:
```bash
composer dev       # 同時実行: Laravel server、queue listener、logs、Vite HMR
npm run dev        # Vite dev server (HMR ポート 5173、プラグイン経由でマッピング)
npm run build      # 本番バンドル (Vite + Tailwind)
```

**テスト**:
```bash
composer test      # config キャッシュをクリア + PHPUnit実行 (Unit + Feature スイート)
php artisan test   # 代替: テストを直接実行
```

**データベース**:
- 開発: SQLite (`.env: DB_CONNECTION=sqlite`)
- テスト: 別の `testing` データベース ([phpunit.xml](../phpunit.xml) で分離)
- マイグレーション実行: `php artisan migrate` (`--seed` でシーダーを実行)

**その他の便利なコマンド**:
```bash
php artisan tinker                     # モデル/ロジック テスト用REPL
php artisan make:model ModelName       # モデル + マイグレーション生成
php artisan make:controller CtrlName   # コントローラ生成
php artisan make:test FeatureTestName # テスト生成
```

## プロジェクト慣例

**インポートと Laravel ヘルパー**:
- 特定のファサードをインポート: `use Illuminate\Support\Facades\DB;` (グローバルヘルパーではなく)
- 生クエリにのみ `DB::` を使用、Eloquent モデルを推奨
- セッション: `session('key')` または `request()->session()->get('key')`

**エラーハンドリング**:
- Laravel 例外は `\Exception` を拡張 (ExceptionHandler でキャッチ)
- バリデーション: `Request::validate()` を使用して自動的に `ValidationException` をスロー

**環境変数** ([.env](../.env)):
- `APP_ENV=local`, `APP_DEBUG=true` (開発); 本番は `production`, `false`
- データベース: 本番環境は MySQL (設定準備済み、`DB_*` 変数を更新)
- キュー: 非同期タスクが必要な場合は `database` または `redis` に設定

**フロントエンド統合**:
- エントリー: [resources/js/app.js](../resources/js/app.js) (bootstrap をインポート)
- Axios は [bootstrap.js](../resources/js/bootstrap.js) で `X-Requested-With` ヘッダー事前設定済み
- Tailwind は [resources/views/](../resources/views/), [resources/js/](../resources/js/) のクラスをスキャン
- Blade で `@vite('resources/css/app.css')` を使用してコンパイル済みアセットを読み込み

## 統合ポイント

**データベースドライバ**:
- クエリ用 Eloquent ORM (`protected $casts` で型の自動キャスト)
- 複雑な条件用の組み込みクエリビルダ

**認証** (組み込み):
- ユーザーモデル: [app/Models/User.php](../app/Models/User.php)
- 認証ミドルウェア利用可、ルートで `middleware('auth')` 使用可
- パスワードリセットトークンは `password_reset_tokens` テーブルに保存

**ジョブキュー** (非同期タスクが必要な場合):
- `database` ドライバで設定、マイグレーションにジョブテーブル
- ディスパッチ: コントローラで `YourJob::dispatch()`
- リッスン: `php artisan queue:listen` (dev server の一部)

**メール** (必要に応じて):
- [config/mail.php](../config/mail.php) で設定
- `Mail::send()` または Mail ジョブクラス経由で送信

**フロントエンドフレームワーク** (将来):
- Vite プラグイン (Vue 3 または React 対応)
- パッケージをインストール: `npm install react` + `npm install @vitejs/plugin-react`
- [vite.config.js](../vite.config.js) に React プラグインを追加、`.jsx` コンポーネント作成
- 例: `resources/js/components/Counter.jsx` → Blade にインポート

## セキュリティ

**CSRF保護**: フォームで自動有効。Blade フォームに `@csrf` を含めるか、JS から `X-CSRF-TOKEN` ヘッダーを送信。

**機密エリア**:
- データベース認証情報: `.env` を使用 (`.env.example` のみコミット、`.env` はコミットしない)
- API認証: Sanctum インストール後に `auth:sanctum` ミドルウェアを使用
- ユーザー入力: コントローラまたはフォームリクエストで常にバリデーション

**パスワードセキュリティ**:
- ハッシング: Bcrypt (ラウンド数設定可: [.env](../.env) で `BCRYPT_ROUNDS=12`)
- 平文保存禁止、常に保存前にハッシュ

## クイックリファレンス

| タスク | ファイル/コマンド |
|--------|-----------------|
| モデルを追加 | `php artisan make:model Post --migration` (マイグレーションも自動生成) |
| コントローラを追加 | `php artisan make:controller PostController --resource` |
| テストを書く | `php artisan make:test Feature/PostTest` → アサーションを追加 |
| マイグレーション作成 | `php artisan make:migration create_posts_table` |
| マイグレーション実行 | `php artisan migrate` |
| データベースをシード | `php artisan db:seed` または `php artisan migrate --seed` |
| ルート確認 | `php artisan route:list` |
| リクエストをデバッグ | `dd($request->all())` または Tinker 使用 |
