<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $definitions = [
            'pwd_status' => fn (Blueprint $table) => $table->string('pwd_status', 20)->default('not_declared'),
            'pwd_id_front' => fn (Blueprint $table) => $table->string('pwd_id_front')->nullable(),
            'pwd_id_back' => fn (Blueprint $table) => $table->string('pwd_id_back')->nullable(),
            'pwd_verified_at' => fn (Blueprint $table) => $table->timestamp('pwd_verified_at')->nullable(),
            'pwd_verified_by' => fn (Blueprint $table) => $table->unsignedBigInteger('pwd_verified_by')->nullable(),
            'pwd_rejection_reason' => fn (Blueprint $table) => $table->text('pwd_rejection_reason')->nullable(),
            'student_status' => fn (Blueprint $table) => $table->string('student_status', 20)->default('not_declared'),
            'student_proof' => fn (Blueprint $table) => $table->string('student_proof')->nullable(),
            'student_verified_at' => fn (Blueprint $table) => $table->timestamp('student_verified_at')->nullable(),
            'student_verified_by' => fn (Blueprint $table) => $table->unsignedBigInteger('student_verified_by')->nullable(),
            'student_rejection_reason' => fn (Blueprint $table) => $table->text('student_rejection_reason')->nullable(),
        ];

        foreach ($definitions as $column => $addColumn) {
            if (Schema::hasColumn('residents', $column)) {
                continue;
            }

            Schema::table('residents', function (Blueprint $table) use ($addColumn): void {
                $addColumn($table);
            });
        }
    }

    public function down(): void
    {
        // Repair-only migration: do not remove evidence columns during rollback.
    }
};
