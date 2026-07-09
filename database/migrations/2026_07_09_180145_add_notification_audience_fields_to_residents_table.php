<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            if (! Schema::hasColumn('residents', 'is_pwd')) {
                $table->boolean('is_pwd')->default(false);
            }

            if (! Schema::hasColumn('residents', 'is_student')) {
                $table->boolean('is_student')->default(false);
            }

            if (! Schema::hasColumn('residents', 'gender')) {
                $table->string('gender', 20)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            if (Schema::hasColumn('residents', 'is_pwd')) {
                $table->dropColumn('is_pwd');
            }

            if (Schema::hasColumn('residents', 'is_student')) {
                $table->dropColumn('is_student');
            }

            if (Schema::hasColumn('residents', 'gender')) {
                $table->dropColumn('gender');
            }
        });
    }
};