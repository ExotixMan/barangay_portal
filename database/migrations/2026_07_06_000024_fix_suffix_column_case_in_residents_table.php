<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        
        try {
            DB::statement('ALTER TABLE residents RENAME COLUMN "Suffix" TO suffix');
        } catch (\Exception $e) {
            
        }
    }

    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE residents RENAME COLUMN suffix TO "Suffix"');
        } catch (\Exception $e) {
            
        }
    }
};
