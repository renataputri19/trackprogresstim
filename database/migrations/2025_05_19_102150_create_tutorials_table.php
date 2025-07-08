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
        Schema::create('tutorials', function (Blueprint $table) {
            $table->id();
            $table->string('category')->index(); // e.g., Dasar, Sistem Inti
            $table->string('title'); // e.g., Memulai dengan RENTAK
            $table->text('description'); // Short description of the tutorial
            $table->string('slug')->unique(); // URL-friendly identifier, e.g., memulai-dengan-rentak
            $table->string('duration'); // e.g., 5:30
            $table->string('thumbnail')->nullable(); // URL or path to thumbnail
            $table->string('video_url'); // e.g., YouTube embed URL
            $table->text('chapters')->nullable(); // JSON or text for chapters (e.g., Introduction: 0:00-2:15)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutorials');
    }
};