# Render deployment setup

This project is configured to deploy with the root `Dockerfile`.

## Required Render environment variables

Copy the values you actually use into Render's Environment settings. Do **not** upload or commit a real `.env` file.

Required application values:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://YOUR-SERVICE.onrender.com`
- `APP_KEY=` a persistent Laravel key generated once with `php artisan key:generate --show`
- `RUN_MIGRATIONS=true`

Required PostgreSQL / Supabase values:

- `DB_CONNECTION=pgsql`
- `DB_HOST=` your Supabase **Session Pooler** host
- `DB_PORT=5432`
- `DB_DATABASE=postgres`
- `DB_USERNAME=` your Session Pooler username
- `DB_PASSWORD=` your database password
- `DB_SSLMODE=require`
- `DB_SEARCH_PATH=public`

The Docker startup runs `php artisan migrate --force` when `RUN_MIGRATIONS=true`, so the repair migration in this package is applied to an existing database automatically.

## Supabase private storage

Set the Supabase storage variables from `.env.example` and keep `SUPABASE_STORAGE_REQUIRED=true` in production. Sensitive resident documents are not included in the deployment archive and should be stored in the configured private bucket, not under `public/uploads`.

If an API key was ever stored in `storage/app/admin-settings.json` in an older copy of the project, rotate that credential before production. The repaired package intentionally contains no such credential file.

## Broadcasting

The default production example uses `BROADCAST_CONNECTION=null`. Admin notifications still refresh through the application's existing polling logic. If you later run Laravel Reverb as a separate persistent service, configure the Reverb variables and switch the broadcast connection deliberately.

## Runtime server

The Docker image runs Apache with Laravel's `public` directory as the document root and binds Apache to Render's `PORT` value (default fallback: `10000`). It does not use `php artisan serve` in production.

## Before the first live deployment

1. Keep your real secrets only in Render/Supabase environment settings.
2. Rotate any credential that existed in an older ZIP or `admin-settings.json`.
3. Verify the live Supabase project's grants/RLS policy for any schema exposed through the Supabase Data API.
4. Deploy the Docker service and check the Render logs for the migration completion.
5. Smoke-test resident registration/login, admin login, document requests, announcements, events, projects, private uploads/downloads, and analytics.
