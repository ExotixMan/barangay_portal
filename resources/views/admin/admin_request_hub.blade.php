<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Service Requests</title>
<style>body{font-family:Arial,sans-serif;background:#f6f7fb;margin:0;padding:40px}.wrap{max-width:900px;margin:auto}.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px}.card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:22px;box-shadow:0 4px 14px rgba(0,0,0,.05)}a{display:inline-block;margin-top:12px;text-decoration:none;font-weight:700}h1{margin-bottom:8px}.muted{color:#667085}</style></head>
<body><main class="wrap"><h1>Service Requests</h1><p class="muted">Choose a request module you are permitted to access.</p><div class="cards">
@if($canClearance)<section class="card"><h2>Barangay Clearance</h2><a href="{{ route('admin.clearance.index') }}">Open requests →</a></section>@endif
@if($canResidency)<section class="card"><h2>Residency</h2><a href="{{ route('admin.residency.index') }}">Open requests →</a></section>@endif
@if($canIndigency)<section class="card"><h2>Indigency</h2><a href="{{ route('admin.indigency.index') }}">Open requests →</a></section>@endif
@if(!$canClearance && !$canResidency && !$canIndigency)<section class="card"><h2>No module permission</h2><p class="muted">Your account has View Requests but no specific document-module permission. Ask a Super Admin to update your permissions.</p></section>@endif
</div></main></body></html>
