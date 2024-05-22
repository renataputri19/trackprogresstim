<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('team_leader');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('target');
            $table->integer('progress');
            $table->decimal('percentage', 5, 2)->virtualAs('progress / target * 100');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
