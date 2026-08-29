<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('residency_applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->date('birthdate');
            $table->string('gender');
            $table->string('civil_status');
            $table->string('birth_place');

            $table->text('address');
            $table->string('years_residing');
            $table->string('residency_type');
            $table->string('contact_number');
            $table->string('email');
            $table->integer('household_members');

            $table->string('purpose');
            $table->text('purpose_other')->nullable();

            $table->string('primary_proof');
            $table->string('government_id');

            $table->string('status')->default('pending');
            $table->timestamp('status_updated_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residency_applications');
    }
};
