<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Verified profiles reuse their approved ID; a separate upload is waived.
        Schema::table('residency_applications', function (Blueprint $table) {
            $table->string('primary_proof')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Existing waived requests must be retained when rolling back the release.
        // Restore the constraint only when every request has an uploaded proof.
        if (! \Illuminate\Support\Facades\DB::table('residency_applications')->whereNull('primary_proof')->exists()) {
            Schema::table('residency_applications', function (Blueprint $table) {
                $table->string('primary_proof')->nullable(false)->change();
            });
        }
    }
};
