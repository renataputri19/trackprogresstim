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
        Schema::table('sbr_businesses', function (Blueprint $table) {
            // Drop old composite unique constraint on name/location
            // Requires doctrine/dbal to drop by columns
            $table->dropUnique(['nama_usaha', 'kecamatan', 'kelurahan']);

            // Add unique constraint on idsbr (allows multiple NULLs in MySQL)
            $table->unique('idsbr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sbr_businesses', function (Blueprint $table) {
            // Remove unique on idsbr
            $table->dropUnique(['idsbr']);

            // Restore composite unique on name/location
            $table->unique(['nama_usaha', 'kecamatan', 'kelurahan']);
        });
    }
};