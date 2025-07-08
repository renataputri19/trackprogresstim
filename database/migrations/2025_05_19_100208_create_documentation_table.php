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
        Schema::create('documentation', function (Blueprint $table) {
            $table->id();
            $table->string('category')->index(); // e.g., Memulai, Sistem Inti, Administrasi
            $table->string('title'); // e.g., Pengenalan RENTAK
            $table->text('description'); // Short description of the document
            $table->string('slug')->unique(); // URL-friendly identifier, e.g., pengenalan-rentak
            $table->string('icon')->nullable(); // Icon for category, e.g., rocket, layout-dashboard
            $table->text('content')->nullable(); // Detailed content for the documentation
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation');
    }
};