<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('criteria', function (Blueprint $table) {
            $table->unsignedSmallInteger('year')->default(2025)->after('last_updated_by')->index();
        });

        // Ensure existing rows are marked as 2025 explicitly (for historical data)
        if (Schema::hasColumn('criteria', 'year')) {
            DB::table('criteria')->whereNull('year')->update(['year' => 2025]);
        }
    }

    public function down(): void
    {
        Schema::table('criteria', function (Blueprint $table) {
            if (Schema::hasColumn('criteria', 'year')) {
                $table->dropColumn('year');
            }
        });
    }
};