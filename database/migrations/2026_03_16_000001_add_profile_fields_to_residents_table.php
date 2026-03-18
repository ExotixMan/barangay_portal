<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            if (!Schema::hasColumn('residents', 'middlename')) {
                $table->string('middlename')->nullable()->after('firstname');
            }
            if (!Schema::hasColumn('residents', 'suffix')) {
                $table->string('suffix', 10)->nullable()->after('lastname');
            }
            if (!Schema::hasColumn('residents', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('valid_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumnIfExists('middlename');
            $table->dropColumnIfExists('suffix');
            $table->dropColumnIfExists('profile_photo');
        });
    }
};
