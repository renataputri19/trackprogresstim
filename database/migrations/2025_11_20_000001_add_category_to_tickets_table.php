<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Add category column
            $table->string('category')->nullable()->after('service_type');
        });

        // Migrate existing data: map service_type to category
        DB::table('tickets')
            ->where('service_type', 'map_request')
            ->update(['category' => 'Peta Cetak']);

        DB::table('tickets')
            ->where('service_type', 'ticket')
            ->update(['category' => 'Lainnya']);

        // Make category required after migration
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('category')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};

