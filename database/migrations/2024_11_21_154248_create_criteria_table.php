<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->string('penilaian'); // New column for Penilaian
            $table->text('kriteria_nilai'); // Existing column for description
            $table->string('pilihan_jawaban'); // Static
            $table->string('jawaban_unit')->nullable(); // Dynamic
            $table->decimal('nilai_unit', 5, 2)->nullable(); // Dynamic
            $table->text('catatan_unit')->nullable(); // Dynamic
            $table->string('bukti_dukung_unit')->nullable(); // Dynamic
            $table->string('url_bukti_dukung')->nullable(); // Dynamic
            $table->string('jawaban_tpi')->nullable(); // Dynamic
            $table->decimal('nilai_tpi', 5, 2)->nullable(); // Dynamic
            $table->text('catatan_reviu_tpi')->nullable(); // Dynamic
            $table->string('category'); // Category for filtering
            $table->string('last_updated_by')->nullable(); // Category for filtering
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criteria');
    }
};
