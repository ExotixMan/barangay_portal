# Users & Roles performance and record exports

## Users & Roles

The page previously queried the database for individual permission checkboxes, queried the permission catalog repeatedly from Blade, and loaded role members separately for each role. The `users_count` accessor also discarded eager-loaded counts and ran fresh counts instead.

Permission checks now reuse the current model's loaded role and direct permissions. There is no persistent permission-grant cache: a new request reads current grants, and the regression suite verifies that revoked access is denied. Role member dialogs share one directory query, aggregate counts are reused, and Blade receives one grouped permission catalog.

User permission and role editors load only when opened. Failed loads have a retry action, and failed form submissions render their old values and validation errors inline so they survive the redirect. Deferred editors attach their existing validators when loaded and preserve Bootstrap's dialog element for focus and transitions. The editors retain their existing permission gates and forms.

An isolated test with 12 roles, 12 administrators and 127 permissions dropped from **3,234 queries to 40**, with initial HTML reduced from approximately **2.54 MB to 0.51 MB**. Browser checks loaded the local page in under one second at desktop and mobile widths. These timings describe the isolated test environment, not a measurement of the live deployment. Tests also bound query count inside the deferred editor itself.

## Shared exports

Residents, residency applications, indigency applications, barangay clearances and incident reports use one export service and one format-selection dialog. The default is an Excel workbook; CSV remains available. Their leading columns are:

`ID, Reference Number, Full Name, Contact Number, Email, Address, Status, Created At, Updated At`

Module-specific details follow those columns. Resident reference numbers are blank, and the common name/contact/address fields in incident exports refer to the complainant. Respondent details remain in their own columns. Clearance fees and existing module details remain included.

- Valid Philippine mobile formats such as `09123456789`, `+639123456789`, `639123456789`, and `9123456789` export as `09123456789`. Incident complainant and respondent numbers both use this conversion. Missing, malformed and landline values are preserved rather than fabricated.
- Excel uses explicit text cells, so opening the workbook retains the leading zero. Every workbook uses the same header styling, column widths, frozen header and column filters.
- CSV contains the normalized phone value with its leading zero. CSV has no cell-type metadata: when importing it into Excel, set phone columns to **Text**. The export dialog explains this; use the default Excel format for direct opening without import settings.
- Exporting selected rows preserves the selection. With no selection, the export includes all records in that module except archived records, matching the existing export scope. Repeated downloads replace prior hidden IDs so choosing “all” cannot accidentally reuse an earlier selection.
- Rows are read in batches of 500. Excel generation uses OpenSpout and temporary files outside the public directory, with cleanup after streaming. Resident-supplied values stay text in Excel; CSV formula-like values are escaped.

The audit-log CSV keeps its audit-specific columns; it is not a contact-list export.

## Updating and verification

After pulling `production`, run `composer install` to install the new locked OpenSpout dependency, then `php artisan optimize:clear` and `npm run build`. In Windows PowerShell, use `npm.cmd run build` if script execution is disabled. PHP 8.4 with ZIP and XML extensions remains the recommended runtime. No new database migration is needed for this changeset.

Verification passed:

- **105 PHP tests, 964 assertions** on isolated SQLite and PostgreSQL databases, including selected/all exports in both formats for all five modules, phone variants, archived exclusions, formula safety, export permissions, batch boundaries, permission revocation, deferred editors, retry-related endpoints and validation redirects.
- **20 actual browser downloads** across desktop (1440px) and mobile (390px). All downloaded files were inspected for the shared contact column and `09` value; Excel cells were verified to be text. Browser checks also saved user permissions and edited a role, retried a failed editor load, cancelled exports and cleared prior selections before an all-record export.
- JavaScript syntax checks, PHP formatting, dependency platform checks, Composer validation and the frontend build passed. Existing Composer warnings about unrelated unbounded dependencies remain.
- All **8 Python prediction tests** and the dashboard browser regression passed.

The populated incident-page browser check also exposed a helper defined inside the wrong JavaScript scope. Its edit forms now share the helper correctly instead of raising `bindClearInvalidOnEdit is not defined`.
