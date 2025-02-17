<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('criteria', function (Blueprint $table) {
            $table->text('bukti_dukung_unit')->change(); // Change column type from string to text
        });
    }

    public function down()
    {
        Schema::table('criteria', function (Blueprint $table) {
            $table->string('bukti_dukung_unit', 255)->change(); // Revert back if needed
        });
    }
};

