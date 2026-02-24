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
        Schema::create('barangay_clearances', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();

            // Personal Info
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->date('birthdate');
            $table->string('gender');
            $table->text('address');
            $table->string('contact_number');
            $table->string('email');

            // Requirement
            $table->string('valid_id_path');
            $table->string('purpose');
            $table->text('purpose_other')->nullable();

            // Status
            $table->string('status')->default('processing');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangay_clearances');
    }
};
