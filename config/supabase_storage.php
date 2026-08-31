<?php

return [
    'url' => rtrim((string) env('SUPABASE_URL', ''), '/'),
    'secret_key' => (string) env('SUPABASE_SECRET_KEY', env('SUPABASE_SERVICE_ROLE_KEY', '')),

    'private_bucket' => env('SUPABASE_STORAGE_PRIVATE_BUCKET', 'barangay-private'),
    'public_bucket' => env('SUPABASE_STORAGE_PUBLIC_BUCKET', 'barangay-public'),

    'timeout' => (int) env('SUPABASE_STORAGE_TIMEOUT', 120),
    'required' => (bool) env('SUPABASE_STORAGE_REQUIRED', false),
];
