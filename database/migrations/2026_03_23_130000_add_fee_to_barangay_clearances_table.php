<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barangay_clearances', function (Blueprint $table) {
            $table->decimal('fee', 10, 2)->nullable()->after('purpose_other');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangay_clearances', function (Blueprint $table) {
            $table->dropColumn('fee');
        });
    }
};
