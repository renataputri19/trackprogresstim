<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToTasksTable extends Migration
{
    // The 'up' method defines the changes to be applied to the database when the migration is run
    public function up()
    {
        // Modify the 'tasks' table
        Schema::table('tasks', function (Blueprint $table) {
            // Add a new column 'user_id' that references the 'id' column on the 'users' table
            // 'foreignId' automatically creates an unsigned big integer column
            // 'constrained' method sets up a foreign key constraint that references the 'id' column on the 'users' table
            // 'onDelete('cascade')' means that if a user is deleted, all related tasks will also be deleted
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    // The 'down' method defines the changes to be applied to the database when the migration is rolled back
    public function down()
    {
        // Modify the 'tasks' table
        Schema::table('tasks', function (Blueprint $table) {
            // Drop the foreign key constraint on the 'user_id' column
            $table->dropForeign(['user_id']);
            // Drop the 'user_id' column from the 'tasks' table
            $table->dropColumn('user_id');
        });
    }
};

