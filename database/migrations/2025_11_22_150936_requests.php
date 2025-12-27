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
       Schema::create('requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->id('resident_id');
            $table->enum('request_type', ['clearance', 'first_time_job_seeker', 'indigency']);
            $table->string('full_name', 150);
            $table->string('complete_address', 255);
            $table->integer('age')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('contact_number', 20);
            $table->string('email_address', 150)->nullable();
            $table->string('purpose_of_request', 150)->nullable();
            $table->string('specify_others', 255)->nullable();
            $table->string('valid_id_path', 255)->nullable();
            $table->string('proof_of_residency_path', 255)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('date_submitted')->useCurrent();
            $table->foreign('resident_id')->references('id')->on('residents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
