<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            if (!Schema::hasColumn('residents', 'valid_id_back')) {
                $table->string('valid_id_back')->nullable();
            }

            if (!Schema::hasColumn('residents', 'valid_id_selfie')) {
                $table->string('valid_id_selfie')->nullable();
            }

            if (!Schema::hasColumn('residents', 'valid_id_status')) {
                $table->string('valid_id_status')->default('for_verification');
            }

            if (!Schema::hasColumn('residents', 'valid_id_verified_at')) {
                $table->timestamp('valid_id_verified_at')->nullable();
            }

            if (!Schema::hasColumn('residents', 'valid_id_verified_by')) {
                $table->unsignedBigInteger('valid_id_verified_by')->nullable();
            }

            if (!Schema::hasColumn('residents', 'valid_id_rejection_reason')) {
                $table->text('valid_id_rejection_reason')->nullable();
            }

            if (!Schema::hasColumn('residents', 'middlename')) {
                $table->string('middlename')->nullable();
            }

            if (!Schema::hasColumn('residents', 'suffix')) {
                $table->string('suffix', 10)->nullable();
            }

            if (!Schema::hasColumn('residents', 'profile_photo')) {
                $table->string('profile_photo')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $columns = [
                'valid_id_back',
                'valid_id_selfie',
                'valid_id_status',
                'valid_id_verified_at',
                'valid_id_verified_by',
                'valid_id_rejection_reason',
                'middlename',
                'suffix',
                'profile_photo',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('residents', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};