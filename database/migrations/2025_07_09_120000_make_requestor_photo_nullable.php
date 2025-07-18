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
            // Make requestor_photo nullable to allow optional photo uploads
            $table->string('requestor_photo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // First, update any NULL values to a default value before making the column NOT NULL
            \Illuminate\Support\Facades\DB::table('tickets')
                ->whereNull('requestor_photo')
                ->update(['requestor_photo' => 'default_photo.jpg']);
            
            // Now safely change the column back to NOT NULL
            $table->string('requestor_photo')->nullable(false)->change();
        });
    }
};
