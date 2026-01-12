<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sbr_businesses', function (Blueprint $table) {
            $table->id();
            $table->string('nama_usaha');
            $table->string('kecamatan', 100);
            $table->string('kelurahan', 100);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('status', ['aktif', 'tutup'])->nullable();
            $table->timestamps();
            
            // Add indexes for efficient search and filtering
            $table->index('kecamatan');
            $table->index('kelurahan');
            $table->index('status');
            $table->index('nama_usaha');

            // Prevent exact duplicate entries for the same business/location combination
            $table->unique(['nama_usaha', 'kecamatan', 'kelurahan']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sbr_businesses');
    }
};

