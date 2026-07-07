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
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id('incident_id');
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            $table->string('full_name', 150);
            $table->string('address', 255);
            $table->string('location', 255);
            $table->dateTime('date_of_incident');
            $table->string('contact_number', 11);
            $table->string('type_of_incident', 255);
            $table->string('description', 255)->nullable();
            $table->string('proof_of_incident', 255)->nullable();
            $table->string('status', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident');
    }
};
