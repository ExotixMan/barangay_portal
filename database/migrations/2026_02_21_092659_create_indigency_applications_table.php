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
        Schema::create('indigency_applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();

            $table->date('birthdate');
            $table->string('gender');

            $table->text('address');
            $table->string('contact_number');
            $table->string('email');

            $table->string('monthly_income');
            $table->integer('household_members');

            $table->string('purpose');
            $table->text('purpose_other')->nullable();

            $table->string('valid_id_path');

            $table->string('status')->default('processing');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indigency_applications');
    }
};
