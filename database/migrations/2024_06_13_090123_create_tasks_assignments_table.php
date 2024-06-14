<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('tasks_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leader_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('target');
            $table->integer('progress_total')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks_assignments');
    }
}
;
