# Prediction and validation corrections

The review covered prediction generation and persistence, dashboard rendering and permissions, archived residency counts, tracking status labels, and form validation. These changes retain the request approval workflow, fee rules and model family.

## Corrected mismatches

- Prediction features now continue the same trend index as training and use the same population standard deviation. Direct forecast calls also exclude today's partial data and future records.
- Sparse history is labelled as a weekday-average estimate. A trained model needs sufficient observations and active days, rather than simply many padded zero calendar days.
- Combined request estimates equal the sum of the three service forecasts for every day and selected horizon. Horizon-specific methods and completed-day history counts are available in the result.
- Forecast queries exclude archived records; draft announcements do not count as publishing activity. ResidencyApplication now observes the same soft-delete scope as the admin residency model.
- Daily predictions expire at Manila midnight as well as by age. Failed refreshes preserve the previous persisted output; overlapping refreshes return a busy result rather than reporting an old result as a new success. Automatic failures have a five-minute retry cooldown.
- Database I/O only runs from the Python CLI, allowing isolated model tests. SQLite file databases and PostgreSQL use the configured driver. Generated JSON is atomically replaced, and PHP uses a unique temporary output per run and validates it before persistence.
- Removed the old committed public forecast snapshot and bytecode artifact. Private forecast storage is the source of truth; empty or malformed data is explicitly unavailable.
- Dashboard HTML loads without synchronously running Python. Forecast responses filter module sections by admin permission. Refresh failures and stale states appear inline; valid zero predictions are distinct from missing history. Chart assets are pinned and served locally.
- Tracking recognizes `under_review` and `ready_pickup`, matching the admin status codes. Residency selections are validated against the form options, with specific readable messages. Common required-field, email and numeric constraints have accessible inline feedback instead of browser validation bubbles.

## Verification

Run PHP checks with `php artisan test`. Prediction-specific tests include refresh failures, storage outages, overlapping runs, stale dates, output cleanup, permissions and archived requests. Residency submission tests continue to cover both reused verification and private uploads.

Run Python checks with `python -m unittest discover -s tests/Analytics -v` after installing `requirements.txt`. Cases cover Manila day boundaries, current-day/future exclusion, identical training/future features, sparse and empty history, a real model fit, sum consistency and database archive filtering.

The actual `php artisan analytics:refresh-forecast` pipeline was exercised against isolated SQLite and PostgreSQL databases. Browser checks cover the admin dashboard at 1440px and 390px, range switching, inline failure feedback, required-field feedback, and a valid zero forecast. No production data or external notification/AI providers are used in these checks.

These checks establish correctness of the tested pipeline and states, not measured forecasting accuracy on real barangay history. See README for Python setup and regeneration after upgrading. The complete production Docker image and live deployment are separate checks; local validation here covers the forecasting process, dependency installation, frontend build and application tests.
