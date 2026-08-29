# Render production setup for BISIG

## 1. Run the new settings migration

This is additive and does not delete existing application/resident data:

```bash
php artisan migrate --path=database/migrations/2026_08_30_000002_create_system_settings_table.php
```

The table makes settings changed in Admin > System Settings persistent on Render. The app still falls back to environment variables if the table is unavailable.

## 2. Email providers

Recommended on Render Free: `resend`, `postmark`, `mailgun`, or `sendgrid` (HTTPS APIs).

You can configure the provider from Admin > System Settings after running the migration, or use environment variables:

```text
MAIL_MAILER=resend
MAIL_FROM_ADDRESS=noreply@your-verified-domain.example
MAIL_FROM_NAME="BISIG Hulong Duhat"
MAIL_API_KEY=your-provider-api-key
```

Provider-specific API-key variables are also accepted as fallbacks:

```text
RESEND_KEY=...
POSTMARK_TOKEN=...
MAILGUN_SECRET=...
SENDGRID_API_KEY=...
```

For Mailgun also set `MAIL_DOMAIN` (or configure the domain in Admin Settings). If using the EU Mailgun API, configure the API endpoint in Admin Settings.

SMTP remains supported for local development, other hosts, or Render paid services where outbound SMTP is available.

## 3. LibreOffice / PDF printing

`App\\Services\\DocumentPdfService` now locates `soffice` automatically and validates the generated PDF. The Render container still needs LibreOffice installed at the operating-system level.

Add this to the existing Debian/Ubuntu-based Dockerfile before the application starts:

```dockerfile
RUN apt-get update && apt-get install -y --no-install-recommends \
    libreoffice-writer \
    libreoffice-core \
    fonts-dejavu-core \
    fonts-liberation \
    && rm -rf /var/lib/apt/lists/*

ENV LIBREOFFICE_BINARY=/usr/bin/soffice
```

For Alpine-based PHP images use the distribution's `libreoffice` package instead.

After deployment, verify from the Render shell:

```bash
which soffice
soffice --version
```

## 4. Uploaded files on Render

Render's normal filesystem is ephemeral. Generated print PDFs can be temporary, but resident Valid IDs/profile uploads should use persistent/object storage in production (for example S3-compatible storage) or a paid persistent disk.
