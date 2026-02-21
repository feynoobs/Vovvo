# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Vovvo is a Laravel 12 + React learning scaffold (React学習用). It is a fresh foundation with no custom business logic yet — only the default User model, authentication infrastructure, and a single welcome route exist.

## Commands

```bash
# One-time setup
composer setup        # install deps, generate key, migrate, npm install, npm build

# Development (runs concurrently: Laravel server, queue listener, log viewer, Vite HMR)
composer dev

# Frontend only
npm run dev           # Vite HMR on port 5173
npm run build         # Production bundle

# Testing
composer test         # clears config cache, then runs full PHPUnit suite
php artisan test --filter=TestName   # run a single test by name/class

# Database
php artisan migrate
php artisan migrate --seed

# Code generation
php artisan make:model Post --migration
php artisan make:controller PostController --resource
php artisan make:test Feature/PostTest

# Docker (Laravel Sail)
./vendor/bin/sail up
./vendor/bin/sail artisan <command>
```

## Architecture

**Request flow:** Route (`routes/web.php` or `routes/api.php`) → Controller → Model / Service → Response

**Layer conventions:**
- `app/Models/` — Eloquent models with relationships, scopes, and type casts
- `app/Http/Controllers/` — thin request handlers; delegate complex logic to services
- `app/Services/` — extract business logic here when controllers grow complex
- `app/Http/Middleware/` — cross-cutting concerns
- `app/Http/Resources/` — API response formatting (when building JSON APIs)
- `app/Providers/AppServiceProvider` — service container bindings and boot logic

**Testing split:**
- `tests/Unit/` — extend `PHPUnit\Framework\TestCase`, no framework booting
- `tests/Feature/` — extend `Tests\TestCase`, full HTTP/DB integration

PHPUnit is configured in `phpunit.xml` to use an in-memory `testing` database, `array` cache/session drivers, and `sync` queue so tests run without side effects.

## Frontend

Assets are managed by Vite. Entry points: `resources/css/app.css` (Tailwind v4) and `resources/js/app.js` (Axios with CSRF headers pre-configured).

In Blade templates, load compiled assets with `@vite(['resources/css/app.css', 'resources/js/app.js'])`.

To add React:
```bash
npm install react react-dom @vitejs/plugin-react
```
Then add the React plugin to `vite.config.js` and create `.jsx` components under `resources/js/components/`.

## Key Conventions

**PHP:** Explicit return types, strong generics (`array<string, string>`), PSR-4 namespaces. Use `protected function casts(): array` syntax (not `$casts` property).

**Database naming:** Tables lowercase plural (`posts`), columns snake_case (`created_at`), foreign keys `{singular}_id` (`user_id`).

**Imports:** Use specific facade imports (`use Illuminate\Support\Facades\DB;`); prefer Eloquent over raw queries.

**Validation:** Use `$request->validate([...])` in controllers — Laravel throws `ValidationException` automatically.

**CSRF:** Include `@csrf` in all Blade forms. JS requests use the Axios `X-CSRF-TOKEN` header already configured in `bootstrap.js`.

**Environment:** Dev uses SQLite (`DB_CONNECTION=sqlite`). Docker Sail provides MySQL 8.4, Redis, Meilisearch, Mailpit, and MinIO for a production-like environment.
