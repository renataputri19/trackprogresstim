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
        Schema::table('tickets', function (Blueprint $table) {
            // Add location fields for map requests
            $table->string('kdkec')->nullable()->after('zone'); // District code
            $table->string('nmkec')->nullable()->after('kdkec'); // District name
            $table->string('kddesa')->nullable()->after('nmkec'); // Village code
            $table->string('nmdesa')->nullable()->after('kddesa'); // Village name
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['kdkec', 'nmkec', 'kddesa', 'nmdesa']);
        });
    }
};
