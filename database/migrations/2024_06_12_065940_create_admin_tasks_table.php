<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('target');
            $table->integer('progress')->default(0);
            $table->string('leader_name'); // Add this line
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_tasks');
    }
};
