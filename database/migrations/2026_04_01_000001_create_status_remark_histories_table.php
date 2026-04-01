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
        Schema::create('status_remark_histories', function (Blueprint $table) {
            $table->id();
            $table->string('request_type', 40);
            $table->unsignedBigInteger('request_id');
            $table->string('reference_number')->nullable();
            $table->string('status', 40);
            $table->text('remarks');
            $table->string('channel', 20)->nullable();
            $table->string('recipient')->nullable();
            $table->foreignId('admin_user_id')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->timestamps();

            $table->index(['request_type', 'request_id']);
            $table->index(['request_type', 'request_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_remark_histories');
    }
};
