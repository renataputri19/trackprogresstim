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
        // Add columns only if they don't already exist (safe re-run after base migration changes)
        if (!Schema::hasColumn('sbr_businesses', 'idsbr') || !Schema::hasColumn('sbr_businesses', 'alamat')) {
            Schema::table('sbr_businesses', function (Blueprint $table) {
                if (!Schema::hasColumn('sbr_businesses', 'idsbr')) {
                    $table->string('idsbr')->nullable()->after('kelurahan');
                }
                if (!Schema::hasColumn('sbr_businesses', 'alamat')) {
                    $table->text('alamat')->nullable()->after('idsbr');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sbr_businesses', function (Blueprint $table) {
            if (Schema::hasColumn('sbr_businesses', 'alamat')) {
                $table->dropColumn('alamat');
            }
            if (Schema::hasColumn('sbr_businesses', 'idsbr')) {
                $table->dropColumn('idsbr');
            }
        });
    }
};