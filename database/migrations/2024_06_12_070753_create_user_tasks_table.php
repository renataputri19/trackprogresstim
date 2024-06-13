<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTasksTable extends Migration
{
    public function up()
    {
        Schema::create('user_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_name');
            $table->string('leader_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('target');
            $table->integer('progress');
            $table->float('percentage')->virtualAs('progress / target * 100');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_tasks');
    }
};
