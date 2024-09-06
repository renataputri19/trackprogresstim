<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimIdToTasksAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::table('tasks_assignments', function (Blueprint $table) {
            $table->unsignedBigInteger('tim_id')->nullable(false); // Ensure it is not nullable
            $table->foreign('tim_id')->references('id')->on('tims')->onDelete('cascade');
        });        
    }

    public function down()
    {
        Schema::table('tasks_assignments', function (Blueprint $table) {
            $table->dropForeign(['tim_id']);
            $table->dropColumn('tim_id');
        });
    }
}

