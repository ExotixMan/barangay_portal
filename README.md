# Laravel + Livewire Starter Kit

## Introduction

Our Laravel + [Livewire](https://livewire.laravel.com) starter kit provides a robust, modern starting point for building Laravel applications with a Livewire frontend.

Livewire is a powerful way of building dynamic, reactive, frontend UIs using just PHP. It's a great fit for teams that primarily use Blade templates and are looking for a simpler alternative to JavaScript-driven SPA frameworks like React and Vue.

This Livewire starter kit utilizes Livewire 3, Laravel Volt, and Tailwind, but **does not include any authentication scaffolding**.

## Official Documentation

Documentation for all Laravel starter kits can be found on the [Laravel website](https://laravel.com/docs/starter-kits).

## Contributing

Thank you for considering contributing to our starter kit! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## License

The Laravel + Livewire starter kit is open-sourced software licensed under the MIT license.

## Development and production checks

Use PHP 8.4 with PDO SQLite/PostgreSQL, mbstring, XML, curl, GD and zip, Composer 2, and Node 22 or newer. Run `composer install` and `npm ci`, then configure a local `.env` and run `php artisan key:generate` once. Keep production credentials out of local test configuration.

- `npm run build` creates `public/build` and copies pinned Bootstrap and icon assets to `public/vendor`. These generated directories are ignored; regenerate them after restoring a fresh checkout. Templates use local assets, so public navigation, forms and modals work without third-party CDNs. OpenCV loads only when the optional guided camera is opened; manual ID upload remains available.
- `php artisan test` runs resident/admin authentication, authorization, private uploads and residency submission regression tests. CI runs on `production`, `main` and `develop`, against both SQLite and PostgreSQL. CI PostgreSQL credentials are for its disposable local service only.
- After deploying this release, run `php artisan migrate --force`. The new migration makes `residency_applications.primary_proof` nullable because residents with verified profiles are not required to upload it again. Uploaded proof remains mandatory for unverified profiles. Back up production data before deployment as usual; this migration does not delete or backfill existing requests.
- The Docker build now builds frontend assets in a Node stage and includes them in the PHP image. `RUN_MIGRATIONS=true` applies migrations during startup. Admin AI diagnostics are under the configured admin prefix, require `system_settings` permission and are rate limited.

For local PostgreSQL integration testing, provide `DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` and `DB_PASSWORD` to the test process. Use a disposable database: test migrations reset it. `phpunit.xml` defaults to in-memory SQLite when no database is supplied. Production PostgreSQL continues to require SSL by default; only a loopback test service without SSL should use `DB_SSLMODE=disable`.

## Prediction setup and checks

Forecast generation needs Python 3.12 or newer and the packages in `requirements.txt`. PHP pages can load without Python, but generating a new forecast requires this setup. On Windows, from the project directory:

```powershell
py -3.12 -m venv .venv
& ".\.venv\Scripts\python.exe" -m pip install -r requirements.txt
```

Set `PYTHON_BINARY` in `.env` to the full interpreter path, for example `C:/HulongDuhat/.venv/Scripts/python.exe`, then run:

```powershell
php artisan config:clear
php artisan analytics:refresh-forecast
& ".\.venv\Scripts\python.exe" -m unittest discover -s tests/Analytics -v
```

On Linux, create a virtual environment with `python3 -m venv .venv`, install the same requirements with `.venv/bin/python -m pip install -r requirements.txt`, and set `PYTHON_BINARY` to its absolute path. Forecasting supports PostgreSQL and file-backed SQLite; a separate Python process cannot read PHP's in-memory SQLite test database.

Forecast history uses non-archived records and completed Manila calendar days, excluding the partial current day. Published announcements and incident dates retain their respective source dates. The three service forecasts add up to the combined request estimate. Each 7/30/90-day horizon identifies its method; sparse history uses a weekday-average fallback rather than claiming a trained model. Empty history is distinct from a valid zero prediction. Results are planning estimates, not accuracy guarantees.

Daily forecasts become stale at Manila midnight. A failed or overlapping refresh keeps the last persisted result; the dashboard labels stale/unavailable data and displays refresh errors inline. Old public forecast snapshots are no longer used as fallback data. Use the scheduler or the explicit refresh command to regenerate private forecast storage after upgrading. Forecast access is filtered by the administrator's module permissions.

The production Docker image pins PHP 8.4 on Debian Trixie so its Python version meets the pinned forecasting packages' minimum (3.12). CI also runs the analytics tests on Python 3.12 and 3.13.
