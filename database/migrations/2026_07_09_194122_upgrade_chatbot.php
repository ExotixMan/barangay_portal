<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            if (Schema::hasTable('chatbot_unmatched') && Schema::hasColumn('chatbot_unmatched', 'resolved')) {
                DB::statement("ALTER TABLE chatbot_unmatched ALTER COLUMN resolved TYPE boolean USING CASE WHEN resolved::text IN ('1','true','t','yes') THEN TRUE ELSE FALSE END");
                DB::statement("ALTER TABLE chatbot_unmatched ALTER COLUMN resolved SET DEFAULT FALSE");
                DB::statement("UPDATE chatbot_unmatched SET resolved = FALSE WHERE resolved IS NULL");
            }

            if (Schema::hasTable('chatbot_messages') && Schema::hasColumn('chatbot_messages', 'matched')) {
                DB::statement("ALTER TABLE chatbot_messages ALTER COLUMN matched TYPE boolean USING CASE WHEN matched::text IN ('1','true','t','yes') THEN TRUE ELSE FALSE END");
                DB::statement("ALTER TABLE chatbot_messages ALTER COLUMN matched SET DEFAULT FALSE");
                DB::statement("UPDATE chatbot_messages SET matched = FALSE WHERE matched IS NULL");
            }
        }
    }

    public function down(): void
    {
        
    }
};
