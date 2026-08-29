<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('blotter_reports', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();

            $table->string('report_type');
            $table->date('incident_date');
            $table->time('incident_time');
            $table->string('location');
            $table->text('description');
            $table->text('immediate_action')->nullable();

            $table->string('complainant_name');
            $table->string('complainant_contact');
            $table->string('complainant_address');
            $table->string('complainant_email')->nullable();

            $table->string('respondent_name')->nullable();
            $table->string('respondent_contact')->nullable();
            $table->string('respondent_address')->nullable();
            $table->text('respondent_description')->nullable();

            $table->enum('confidentiality', ['low','medium','high']);
            $table->text('additional_info')->nullable();

            $table->string('status')->default('processing');

            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blotter_reports');
    }
};
