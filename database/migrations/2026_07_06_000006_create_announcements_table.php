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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            $table->text('content');

            $table->enum('category', [
                'important',
                'events',
                'health',
                'services',
                'infrastructure'
            ]);

            $table->string('image')->nullable();

            $table->boolean('is_featured')->default(false);

            $table->integer('views')->default(0);

            $table->date('published_at')->nullable();

            $table->enum('status', ['draft','published','archived'])
                ->default('published');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
