<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename "Suffix" (capital S, created by earlier migration) to lowercase "suffix"
        try {
            DB::statement('ALTER TABLE residents RENAME COLUMN "Suffix" TO suffix');
        } catch (\Exception $e) {
            // Column already correctly named or doesn't exist — safe to ignore
        }
    }

    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE residents RENAME COLUMN suffix TO "Suffix"');
        } catch (\Exception $e) {
            //
        }
    }
};
