<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimTable extends Migration
{
    public function up()
    {
        Schema::create('tims', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the TIM (e.g., SUBBAGIAN UMUM, SOSIAL, etc.)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tims');
    }
}

