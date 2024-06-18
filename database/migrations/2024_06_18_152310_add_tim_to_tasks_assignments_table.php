<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimToTasksAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::table('tasks_assignments', function (Blueprint $table) {
            $table->string('tim')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tasks_assignments', function (Blueprint $table) {
            $table->dropColumn('tim');
        });
    }
}
;
