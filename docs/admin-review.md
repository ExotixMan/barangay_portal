# Admin review — 15 November 2026

This review covers admin account and role changes, certificate status validation, private document previews, history rendering, and desktop/mobile navigation. Prediction checks from [the prediction review](prediction-review.md) were rerun. Approval statuses, fee rules, notification choices, and forecasting methods remain the same.

## Corrections

- Account managers cannot edit, reset, or grant the super-administrator role. Changes through role membership also enforce this boundary. Removing or disabling the last active owner is rejected inside the transaction, with a role-row lock for competing PostgreSQL changes. Bulk operations reject the current account and super administrators, and status changes require `update_users` permission.
- Removing a member checks that they belong to the specified role. System-role safeguards also recognize the canonical `super_admin` name. Archived admin identifiers are included when allocating the next identifier.
- Activity log details are stored as JSON once and existing double-encoded records remain readable. Previously these values could trigger a type error during an account save. Transaction handlers now roll back on PHP errors as well as exceptions. Account update logs use the model's changed fields, avoiding array-to-string comparisons.
- Permission defaults reset only the role selected in the form. Custom roles without defaults retain their permissions and receive an explanatory field error. Reset audit entries identify the affected role.
- Failed account forms no longer flash passwords into session input. Admin names accept Unicode letters and apostrophes consistently in PHP and JavaScript, including ñ.
- Resident age filtering and counts use date comparisons that work on PostgreSQL and SQLite, including the eighteenth and sixtieth birthdays. Age sorting orders by birthdate.
- Private resident and residency ID documents use the authenticated file route. Residency PDFs have a document link instead of an image preview.
- History values are escaped before insertion into the page. Users & Roles tabs resolve the clicked button correctly through the shared event dispatcher; mobile tabs use a visible two-column layout.
- Admin notices use accessible inline feedback, and confirmation/reason prompts use the locally served dialog component. If confirmation assets are unavailable, the action stays unsubmitted with an explanation. Certificate rejection errors describe the required reason and 40-character limit. Validation errors stay visible; only success alerts auto-dismiss.

## Verification

- `php artisan test`: 78 tests, 309 assertions, passed on isolated SQLite and PostgreSQL databases. New cases cover successful account saves and audit records, password reset, authorization failures, owner retention, bulk permissions, role membership, selected-role defaults, password session input, private document routes, age boundaries, Unicode names, and residency rejection/history.
- `python -m unittest discover -s tests/Analytics -v`: all 8 prediction tests passed. The dashboard browser regression also passed for range switching, a valid zero forecast, and inline refresh failure feedback.
- `npm run build`, admin JavaScript syntax checks, and PHP formatting checks passed.
- Chromium checks covered 14 admin pages at 1440px and 390px, with no page script errors or horizontal page overflow. Additional checks covered confirmation cancel/continue, a required reason and its length limit, missing dialog assets, retained validation errors, tab switching, Unicode names, dark-mode feedback, and HTML in history being displayed as text.

These checks use synthetic data and mocked provider responses. Real SMS/email delivery, Supabase account configuration, forecasting accuracy on live historical data, and deployment are not established by these tests. No new database migration is required for this admin changeset.
