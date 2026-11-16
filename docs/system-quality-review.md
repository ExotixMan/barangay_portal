# Portal quality review — 16 November 2026

This review tested the production branch with synthetic accounts and documents in isolated databases. Confirmed failures were fixed and covered by regression tests. It does not certify that every possible defect is absent or that the live deployment has been verified.

## Fixes

- **Server errors from input:** malformed text arrays, invalid sort directions, dates, audit filters, pagination, record numbers and notification UUIDs now produce validation feedback or a missing-record response before controller normalization or database lookup. Invalid HTML filters redirect to the clean page with errors, avoiding a redirect loop. An empty incident form and empty tracking reference no longer cause type errors.
- **Profile corrections:** all recognized open review states block duplicate corrections and new certificate requests consistently. Invalid corrected names, dates, contact numbers, addresses and email syntax are checked before creating a correction and locking service forms. Approval recognizes the email/address labels submitted by service forms, rejects duplicate identities and oversized values, and preserves the pending request if approval fails. Changing email or phone clears verification belonging to the previous value. Full-name approval still uses the popup with prefilled requested values and authenticated front/back ID previews.
- **Registration and certificate data:** registration accepts a resident on their eighteenth birthday and rejects younger applicants. Resident addresses respect the database's 255-character limit. Manual certificate entries reject future birthdays; residency inputs validate gender, civil status and birthplace length.
- **Content and chatbot:** duplicating a 255-character event title preserves a valid title length. Announcement search works on PostgreSQL and SQLite. Announcement/chatbot model booleans use database-compatible serialization, and unresolved chatbot questions remain visible until resolved on both databases.
- **Staff navigation:** login, the portal home and notification recovery destinations use the staff member's assigned permissions, including permissions granted directly to the account.
- **Page experience:** themed recovery pages cover 403, 404, 419, 429, 500 and 503 responses without exposing exception details. Dark-mode fixes cover forecast empty states, event detail headers, permissions panels, mobile table cells, pagination, action buttons, reset-password guidance and the tracking print page. The QR generator is packaged locally, supports UTF-8 and has a readable fallback. Printed tracking pages retain a white background.

Expected validation, access-denied, correction-lock and rate-limit responses retain their appropriate HTTP status. They are controlled responses, rather than uncaught page failures.

## Verification

| Check | Result |
| --- | --- |
| Full PHP suite, PostgreSQL 17 / PHP 8.4 | 312 tests, 2,207 assertions passed |
| Full PHP suite, SQLite / PHP 8.4 | 312 tests, 2,207 assertions passed |
| New regression coverage | 160 additional tests |
| Browser matrix | 48 URLs at 1440px and 390px, light and dark; 392 page, wizard and dialog states |
| Browser runtime and assets | No uncaught JavaScript errors, HTTP 500 responses or failed local assets in the completed matrix |
| Focused follow-up | Saved-ID correction approval on mobile; required-field feedback on all four resident service forms; QR generation, screen/print colors and remaining mobile dark surfaces passed |
| Document routes | Six Word downloads returned valid ZIP/DOCX data; all three certificate PDF print conversions returned preview pages |
| Analytics | Eight tests passed; real analytics output generated against the isolated PostgreSQL browser database |
| Syntax and style | 216 PHP files and 89 JavaScript files passed syntax checks; changed PHP files passed the CI style check |
| Templates and routes | Blade compilation passed; 220 routes inventoried |
| Frontend | Production build passed; npm audit reported zero vulnerabilities |

Feature tests cover public/resident/admin page rendering, empty submissions, malformed filters, age boundaries, profile correction approval/rejection and rollback, confidential document authorization, account/OTP validation, certificate creation/editing/status/archive/restore, content creation/editing/archive/restore, incident/witness validation, role/account protections, exports, notifications and recovery pages. The browser matrix includes populated records, receipts, announcement details, tracking print, lazy permission editors and admin dialogs. Assertions for unknown notification/file IDs also run against PostgreSQL.

PHP tests prevent unexpected outbound HTTP calls and replace email delivery. Browser records, private IDs, passwords and database contents were synthetic. No real resident data was changed and no real email or SMS was sent.

## Repeat the checks

Install the locked dependencies and build assets before testing:

```sh
composer install --no-interaction --prefer-dist
npm ci
npm run build
php artisan test --compact
python -m unittest discover -s tests/Analytics -v
```

Use a separate test database. For the PostgreSQL run, export `DB_CONNECTION=pgsql`, the isolated database connection variables, and the appropriate `DB_SSLMODE` before running the suite. Local test PostgreSQL used `disable`; hosted production TLS settings must remain appropriate for the host. The existing GitHub Actions workflow runs the PHP tests on both database engines.

## Deployment checks

The Docker build copies the QR library into `public/vendor` during `npm run build`. Deploy the new application image, run pending migrations through the normal deployment process, and clear old Laravel caches. No new migration was added by this review.

After deployment, verify a correction approval using the resident's actual stored ID, a certificate submission/download/print, a contact OTP and an email verification/reset link. Confirm the deployed commit and freshly loaded theme assets if an old appearance persists.

Live PostgreSQL records/schema, provider credentials and delivery, storage permissions/object availability, concurrency under production traffic and the original generic 500's production stack trace were unavailable here. Missing legacy uploads still require their original files; a code change cannot recreate those bytes. These remain deployment checks, not verified live outcomes.
