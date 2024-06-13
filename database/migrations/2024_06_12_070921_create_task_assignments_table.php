<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('task_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('task_name');
            $table->string('leader_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('target');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_assignments');
    }
};
