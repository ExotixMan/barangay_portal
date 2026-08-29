<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residency_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('residency_applications', 'government_id_back')) {
                $table->string('government_id_back')->nullable();
            }
        });

        Schema::table('indigency_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('indigency_applications', 'civil_status')) {
                $table->string('civil_status')->nullable();
            }
            if (! Schema::hasColumn('indigency_applications', 'birth_place')) {
                $table->string('birth_place')->nullable();
            }
            if (! Schema::hasColumn('indigency_applications', 'valid_id_back_path')) {
                $table->string('valid_id_back_path')->nullable();
            }
        });

        Schema::table('barangay_clearances', function (Blueprint $table) {
            if (! Schema::hasColumn('barangay_clearances', 'civil_status')) {
                $table->string('civil_status')->nullable();
            }
            if (! Schema::hasColumn('barangay_clearances', 'birth_place')) {
                $table->string('birth_place')->nullable();
            }
            if (! Schema::hasColumn('barangay_clearances', 'valid_id_back_path')) {
                $table->string('valid_id_back_path')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('residency_applications', function (Blueprint $table) {
            if (Schema::hasColumn('residency_applications', 'government_id_back')) {
                $table->dropColumn('government_id_back');
            }
        });

        Schema::table('indigency_applications', function (Blueprint $table) {
            $columns = array_values(array_filter([
                Schema::hasColumn('indigency_applications', 'civil_status') ? 'civil_status' : null,
                Schema::hasColumn('indigency_applications', 'birth_place') ? 'birth_place' : null,
                Schema::hasColumn('indigency_applications', 'valid_id_back_path') ? 'valid_id_back_path' : null,
            ]));
            if ($columns) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('barangay_clearances', function (Blueprint $table) {
            $columns = array_values(array_filter([
                Schema::hasColumn('barangay_clearances', 'civil_status') ? 'civil_status' : null,
                Schema::hasColumn('barangay_clearances', 'birth_place') ? 'birth_place' : null,
                Schema::hasColumn('barangay_clearances', 'valid_id_back_path') ? 'valid_id_back_path' : null,
            ]));
            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
