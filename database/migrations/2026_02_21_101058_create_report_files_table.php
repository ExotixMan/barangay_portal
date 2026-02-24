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
        Schema::create('report_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blotter_report_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_type'); // photo, video, document
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_files');
    }
};
