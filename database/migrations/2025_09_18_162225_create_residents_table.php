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
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('address');
            $table->date('birthdate');
            $table->string('contact', 11);
            $table->string('username')->unique();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->timestamp('email_verified_at');
            $table->boolean('phone_verified')->default(false);
            $table->string('phone_otp')->nullable();
            $table->timestamp('phone_otp_expires_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
