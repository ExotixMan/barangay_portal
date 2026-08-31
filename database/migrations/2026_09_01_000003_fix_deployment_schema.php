<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('events') && ! Schema::hasColumn('events', 'image')) {
            Schema::table('events', function (Blueprint $table) {
                $table->string('image')->nullable();
            });
        }

        if (Schema::hasTable('resident_profile_correction_concerns')
            && ! Schema::hasColumn('resident_profile_correction_concerns', 'remarks')) {
            Schema::table('resident_profile_correction_concerns', function (Blueprint $table) {
                $table->text('remarks')->nullable();
            });
        }

        if (Schema::hasTable('announcements') && DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE announcements DROP CONSTRAINT IF EXISTS announcements_category_check');
            DB::statement("ALTER TABLE announcements ADD CONSTRAINT announcements_category_check CHECK (category IN ('important', 'events', 'health', 'services', 'infrastructure', 'other'))");
            DB::statement('ALTER TABLE announcements ALTER COLUMN published_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING published_at::timestamp');
        }
    }

    public function down(): void
    {
        // Repair-only migration: keep the compatibility columns/checks on rollback
        // so existing production data is not truncated or made invalid.
    }
};
