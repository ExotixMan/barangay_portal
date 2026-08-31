<?php

return [
    'python_binary' => env('PYTHON_BINARY', 'python'),
    'forecast_object' => env('FORECAST_STORAGE_OBJECT', 'analytics/forecast.json'),
    'forecast_max_age_hours' => (int) env('FORECAST_MAX_AGE_HOURS', 30),

    'backup_remote_prefix' => env('BACKUP_REMOTE_PREFIX', 'system-backups'),
    'backup_retention_count' => (int) env('BACKUP_RETENTION_COUNT', 4),
    'backup_chunk_size_mb' => (int) env('BACKUP_CHUNK_SIZE_MB', 45),
    'backup_db_host' => env('BACKUP_DB_HOST'),
    'backup_db_port' => env('BACKUP_DB_PORT'),

    'maintenance_token' => (string) env('MAINTENANCE_TOKEN', ''),
    'request_catchup' => filter_var(env('MAINTENANCE_REQUEST_CATCHUP', true), FILTER_VALIDATE_BOOL),
];
