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
            $table->string('idsbr')->nullable()->after('kelurahan');
            $table->text('alamat')->nullable()->after('idsbr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sbr_businesses', function (Blueprint $table) {
            $table->dropColumn(['idsbr', 'alamat']);
        });
    }
};