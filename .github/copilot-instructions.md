# Project Guidelines – Vovvo (Laravel 12 + React Learning)

## Code Style

**PHP (8.2+)**: Use explicit return types, strong typing, and PSR-4 namespaces.
- ✅ `protected function casts(): array { ... }`
- ✅ Type hints with generics: `array<string, string>`, `list<string>`
- ✅ Modern PHPDoc with `@use` for generics (see [User.php](../app/Models/User.php))

**Formatting**: PSR-2 style with 4-space indentation. Blade templates use `{{ }}` for output.

**Laravel Conventions**:
- Models in `app/Models/` (Eloquent ORM, use `HasFactory` trait for testing)
- Controllers in `app/Http/Controllers/` (inherit from base `Controller`)
- Routes in `routes/web.php` (HTTP), `routes/api.php` (future APIs)
- Service providers in `app/Providers/` for dependency registration

**Database Naming**:
- Tables: lowercase plural (`users`, `posts`, `comments`)
- Columns: snake_case (`email_verified_at`, `user_id`, `remember_token`)
- ForeignKeys: `{table_singular}_id` (e.g., `user_id` references `users` table)

**Testing**:
- Unit tests: `tests/Unit/` (isolated logic, extend `PHPUnit\Framework\TestCase`)
- Feature tests: `tests/Feature/` (HTTP/integration, extend `Tests\TestCase`)
- Use Laravel factories (see [UserFactory.php](../database/factories/UserFactory.php)) for seeding test data

## Architecture

**Current State**: Fresh Laravel 12 scaffold. No service layer yet; Eloquent models handle data.

**Recommended Patterns**:
1. **Models** (`app/Models/`): Define database relationships and scopes
2. **Controllers** (`app/Http/Controllers/`): Handle request logic, delegate to services or models
3. **Services** (`app/Services/`, if needed): Extract complex business logic
4. **Middleware** (`app/Http/Middleware/`, if needed): Cross-cutting concerns
5. **Resources** (`app/Http/Resources/`, for APIs): Format model responses

**Data Flow**:
- Request → Route (`routes/web.php`) → Controller → Model/Service → Response

**State Management**: 
- Backend: Session via database (configured in [.env](../.env): `SESSION_DRIVER=database`)
- Frontend: Vite + Tailwind, no JS framework yet (prepare for React/Vue integration)

## Build and Test

**Setup**:
```bash
composer setup     # One-time: install, generate key, migrate, npm install, build
```

**Development**:
```bash
composer dev       # Runs concurrently: Laravel server, queue listener, logs, Vite HMR
npm run dev        # Vite dev server (HMR on port 5173, mapped to app via plugin)
npm run build      # Production bundle (Vite + Tailwind)
```

**Testing**:
```bash
composer test      # Clear config cache + run PHPUnit (Unit + Feature suites)
php artisan test   # Alternative: run tests directly
```

**Database**:
- Dev: SQLite (`.env: DB_CONNECTION=sqlite`)
- Test: Separate `testing` database (isolated via [phpunit.xml](../phpunit.xml))
- Run migrations: `php artisan migrate` (use `--seed` to populate seeders)

**Other Useful Commands**:
```bash
php artisan tinker                     # REPL for testing models/logic
php artisan make:model ModelName       # Generate model + migration
php artisan make:controller CtrlName   # Generate controller
php artisan make:test FeatureTestName # Generate test
```

## Project Conventions

**Imports & Laravel Helpers**:
- Import specific facades: `use Illuminate\Support\Facades\DB;` (not global helpers)
- Use `DB::` for raw queries only; prefer Eloquent models
- Session: `session('key')` or `request()->session()->get('key')`

**Error Handling**:
- Laravel exceptions extend `\Exception` (caught by ExceptionHandler)
- Validation: Use `Request::validate()` to throw `ValidationException` automatically

**Environment Variables** ([.env](../.env)):
- `APP_ENV=local`, `APP_DEBUG=true` (dev); set to `production`, `false` for prod
- Database: MySQL in production (config ready, update `DB_*` vars)
- Queue: Set to `database` or `redis` if async tasks needed

**Frontend Integration**:
- Entry: [resources/js/app.js](../resources/js/app.js) (imports bootstrap)
- Axios pre-configured with `X-Requested-With` header in [bootstrap.js](../resources/js/bootstrap.js)
- Tailwind scans [resources/views/](../resources/views/), [resources/js/](../resources/js/) for classes
- Use `@vite('resources/css/app.css')` in Blade to load compiled assets

## Integration Points

**Database Drivers**:
- Eloquent ORM for queries (models auto-cast types via `protected $casts`)
- Built-in query builder for complex conditions

**Authentication** (built-in):
- User model at [app/Models/User.php](../app/Models/User.php)
- Auth middleware available; routes can use `middleware('auth')`
- Password reset tokens stored in `password_reset_tokens` table

**Job Queue** (if async tasks needed):
- Configured to `database` driver; jobs table in migrations
- Dispatch: `YourJob::dispatch()` in controllers
- Listen: `php artisan queue:listen` (part of dev server)

**Mailing** (if needed):
- Configure in [config/mail.php](../config/mail.php)
- Send via `Mail::send()` or Mail job classes

**Frontend Framework** (future):
- Vite plugin ready for Vue 3 or React
- Install package: `npm install react` + `npm install @vitejs/plugin-react`
- Update [vite.config.js](../vite.config.js) with React plugin, create `.jsx` components
- Example: `resources/js/components/Counter.jsx` → import in Blade

## Security

**CSRF Protection**: Automatically enabled on forms; include `@csrf` in Blade forms or send `X-CSRF-TOKEN` header from JS.

**Sensitive Areas**:
- Database credentials: Use `.env` (never commit `.env`, only `.env.example`)
- API authentication: Use `auth:sanctum` middleware once Sanctum is installed
- User input: Always validate in controllers or use form requests

**Password Security**:
- Hashing: Bcrypt (rounds configurable: `BCRYPT_ROUNDS=12` in [.env](../.env))
- Never store plain text; always hash before saving

## Quick Reference

| Task | File/Command |
|------|--------------|
| Add a model | `php artisan make:model Post --migration` (auto-creates migration) |
| Add a controller | `php artisan make:controller PostController --resource` |
| Write a test | `php artisan make:test Feature/PostTest` → add assertions |
| Create migration | `php artisan make:migration create_posts_table` |
| Run migrations | `php artisan migrate` |
| Seed database | `php artisan db:seed` or `php artisan migrate --seed` |
| Check routes | `php artisan route:list` |
| Debug request | `dd($request->all())` or use Tinker |
