<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            if (! Schema::hasColumn('residents', 'pwd_status')) {
                $table->string('pwd_status', 20)->default('not_declared');
            }
            if (! Schema::hasColumn('residents', 'pwd_id_front')) {
                $table->string('pwd_id_front')->nullable();
            }
            if (! Schema::hasColumn('residents', 'pwd_id_back')) {
                $table->string('pwd_id_back')->nullable();
            }
            if (! Schema::hasColumn('residents', 'pwd_verified_at')) {
                $table->timestamp('pwd_verified_at')->nullable();
            }
            if (! Schema::hasColumn('residents', 'pwd_verified_by')) {
                $table->unsignedBigInteger('pwd_verified_by')->nullable();
            }
            if (! Schema::hasColumn('residents', 'pwd_rejection_reason')) {
                $table->text('pwd_rejection_reason')->nullable();
            }

            if (! Schema::hasColumn('residents', 'student_status')) {
                $table->string('student_status', 20)->default('not_declared');
            }
            if (! Schema::hasColumn('residents', 'student_proof')) {
                $table->string('student_proof')->nullable();
            }
            if (! Schema::hasColumn('residents', 'student_verified_at')) {
                $table->timestamp('student_verified_at')->nullable();
            }
            if (! Schema::hasColumn('residents', 'student_verified_by')) {
                $table->unsignedBigInteger('student_verified_by')->nullable();
            }
            if (! Schema::hasColumn('residents', 'student_rejection_reason')) {
                $table->text('student_rejection_reason')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $columns = [
                'pwd_status',
                'pwd_id_front',
                'pwd_id_back',
                'pwd_verified_at',
                'pwd_verified_by',
                'pwd_rejection_reason',
                'student_status',
                'student_proof',
                'student_verified_at',
                'student_verified_by',
                'student_rejection_reason',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('residents', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
