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
        Schema::create('blotter_reports', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();

            // incident
            $table->string('report_type');
            $table->date('incident_date');
            $table->time('incident_time');
            $table->string('location');
            $table->text('description');
            $table->text('immediate_action')->nullable();

            // complainant
            $table->string('complainant_name');
            $table->string('complainant_contact');
            $table->string('complainant_address');
            $table->string('complainant_email')->nullable();

            // respondent
            $table->string('respondent_name')->nullable();
            $table->string('respondent_contact')->nullable();
            $table->string('respondent_address')->nullable();
            $table->text('respondent_description')->nullable();

            // preferences
            $table->enum('confidentiality', ['public','confidential','anonymous']);
            $table->text('additional_info')->nullable();

            //status
            $table->string('status')->default('processing');

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blotter_reports');
    }
};
