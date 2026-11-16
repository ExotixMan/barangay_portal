# Security, validation and feedback fixes

The production-branch review fixes cover the confirmed findings from 16 November 2026. No production database records or original uploads were accessed during the work.

## Changes

- Indigency sorting accepts only a fixed SQL direction.
- Private files require a database reference and permission to view the owning module. Notification history and linked email/SMS actions require the corresponding module permission. Archived records retain authorized access.
- Generated PDF previews require the matching certificate permission and record; private responses disable caching. Apache blocks direct HTTP access to legacy private upload directories.
- Reset and verification emails use `APP_URL`, independent of incoming host headers. Production accepts only the canonical hostname and explicitly configured additional hosts; `/up` remains available for platform health checks. Forwarded-host headers are no longer trusted.
- Passwords and OTP fields are excluded from flashed old input. Usernames/emails are normalized consistently, uniqueness is checked without case sensitivity, and existing mixed-case records remain usable at login.
- Mobile-number changes require a unique number and an expiring, hashed, resident-bound SMS challenge, with attempt limits and session locking. The current number remains active until verification succeeds; changing it clears previous login OTPs.
- Public email/phone format checks do not reveal account existence and availability routes are throttled. Password-recovery acknowledgements are consistent for known/unknown addresses.
- Certificate purposes/income brackets and incident consents are validated on the server. Incident names support Unicode. Email-provider failures show recovery feedback without an uncaught server error after saving an account change.
- Indigency submissions show an accessible error summary, field messages and the affected step, retaining entered details. Public upload/camera feedback and confirmations use accessible dialogs that follow dark mode. Chatbot errors give retry guidance. Recovery errors remain visible.
- Supported dependency updates include Laravel, Livewire 3, Guzzle, Symfony, PHPWord, PHPUnit, Axios, Vite and SweetAlert2. Composer resolves for the deployed PHP 8.4 runtime and retains the existing Livewire major version. PHP upload limits now accommodate the supported 500 MiB backup and multipart overhead.

## Deploy

1. Set the canonical production URL, for example `APP_URL=https://www.bisighulongduhat.com`. Set `ALLOWED_HOSTS=bisighulongduhat.com` if the alternate hostname reaches PHP rather than redirecting at the edge. Include a Render hostname only if it must serve application traffic. Use hostnames without schemes or paths in `ALLOWED_HOSTS`.
2. Deploy the new Docker image so both lockfiles, copied browser assets, Apache rules and `docker/php/uploads.ini` take effect. For a non-Docker deployment, run `composer install` with PHP 8.4 and `npm ci && npm run build`; apply equivalent PHP limits and web-server deny rules for private upload directories.
3. Apply pending database migrations with `php artisan migrate --force` (Docker startup does this by default). Then refresh Laravel's deployed caches using the existing deployment process (`php artisan optimize:clear`, then `php artisan optimize`). Keep `APP_DEBUG=false`. Confirm the reverse proxy accepts the supported upload sizes.
4. Check an admin role with notification permission but no registry permission: its registry documents/history must be forbidden. Check an authorized role can still open its documents and generated PDFs.
5. Check reset/verification email URLs use the canonical host. Exercise a contact-number change with the real SMS provider and complete verification; the old number should remain unchanged if delivery or verification fails.
6. Keep the private Supabase bucket private. Private uploads now refuse a public or ambiguous bucket configuration. Verify persistent storage and actual production document objects separately.

## Restore missing legacy documents

A database path cannot recreate missing file contents. Obtain the original upload backup and extract it outside the web root, retaining the layout `SOURCE/uploads/valid_id/...`, `SOURCE/uploads/proof_of_residency/...`, etc.

```sh
# Review missing/ready files without writing document bytes.
php artisan documents:restore-legacy /absolute/path/to/SOURCE

# Copy available referenced files to private storage and verify SHA-256 checksums.
php artisan documents:restore-legacy /absolute/path/to/SOURCE --apply
```

The command copies only referenced legacy paths, preserves exact directory suffixes, checks source containment, refuses to overwrite different existing objects, and writes a private manifest under `storage/app/private/legacy-restores/`. Missing/mismatched files produce a nonzero exit status. Successful repeat runs are safe. Database paths are preserved; secure-file handlers resolve the corresponding restored private object. This works with configured Supabase or local private storage; local storage must be persistent across deployments.

Keep the source backup and manifest private. Do not copy originals into a publicly readable uploads directory. On other web servers, block direct requests to `uploads/valid_id`, `uploads/valid_ids`, `uploads/evidences`, `uploads/proof_of_residency` and `uploads/profile_photos` while retaining authorized PHP access.

## Verification and limits

Regression tests cover permission denial/authorized access, SQL manipulation, canonical email links, confidential caching, identity changes, OTP expiry/attempts/replay, enums, consent, delivery failure, visible validation and legacy-document restoration. The full PHP suite passed (135 tests, 1,105 assertions), along with eight analytics tests, the frontend production build, syntax checks for 205 PHP and 87 JavaScript files, Blade/route compilation, and real PHPWord/LibreOffice Word-to-PDF conversion. Chromium verified resident/admin flows, dark-mode headers, styled confirmations, invalid-upload feedback and chatbot failures using synthetic accounts only.

`npm audit` reports zero vulnerabilities after updating. The standalone Composer advisory endpoint is blocked by the cloud environment's network proxy; the updated lockfile is also checked against the FriendsOfPHP advisory snapshot used in the review (commit `eaef283d1621465afae7498a8ab12dc20fabf250`, 13 November 2026). That snapshot has zero matching advisories against the updated lockfile (100 relevant advisory files checked). Rerun `composer audit` on the deployment network for a fresh upstream result.

Local checks use SQLite and mocked email/SMS providers. Actual production PostgreSQL, proxy settings, external delivery, bucket visibility and missing original documents require deployment verification. Passing tests address the confirmed findings; they do not establish that every possible vulnerability or business-logic error is absent.
