<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            if (!Schema::hasColumn('residents', 'gender')) {
                $table->string('gender', 20)->nullable()->after('birthdate');
            }

            if (!Schema::hasColumn('residents', 'civil_status')) {
                $table->string('civil_status', 30)->nullable()->after('gender');
            }

            if (!Schema::hasColumn('residents', 'place_of_birth')) {
                $table->string('place_of_birth')->nullable()->after('civil_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            if (Schema::hasColumn('residents', 'place_of_birth')) {
                $table->dropColumn('place_of_birth');
            }

            if (Schema::hasColumn('residents', 'civil_status')) {
                $table->dropColumn('civil_status');
            }

            if (Schema::hasColumn('residents', 'gender')) {
                $table->dropColumn('gender');
            }
        });
    }
};