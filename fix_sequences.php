<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = ['resident_activities', 'residents'];

foreach ($tables as $table) {
    $max = \Illuminate\Support\Facades\DB::table($table)->max('id') ?? 0;
    \Illuminate\Support\Facades\DB::statement(
        "SELECT setval(pg_get_serial_sequence('$table', 'id'), " . max(1, $max + 1) . ", false)"
    );
    echo "Reset sequence for '$table' to " . max(1, $max + 1) . PHP_EOL;
}
