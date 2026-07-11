<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resident_profile_correction_concerns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resident_id')->nullable();
            $table->string('service')->nullable();
            $table->string('field_name');
            $table->text('current_value')->nullable();
            $table->text('correct_value');
            $table->text('message')->nullable();
            $table->string('status')->default('pending'); // pending, reviewed, corrected, rejected
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['resident_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resident_profile_correction_concerns');
    }
};
