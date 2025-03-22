<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_it_staff')->default(false);
        });

        // Update existing IT staff
        $itStaffEmails = [
            'idon@bps.go.id',
            'retza.anugrah@bps.go.id',
            'moon@bps.go.id',
            'adi.darmanto@bps.go.id',
            'putri.henessa@bps.go.id',
            'maulidfan.ghul@bps.go.id',
        ];
        \App\Models\User::whereIn('email', $itStaffEmails)->update(['is_it_staff' => true]);
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_it_staff');
        });
    }
};