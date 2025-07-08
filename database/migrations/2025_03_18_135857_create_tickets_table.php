<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code')->unique(); // e.g., 202503-001
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Requestor
            $table->string('ruangan'); // Ruangan instead of division
            $table->string('title');
            $table->text('description');
            $table->string('requestor_photo'); // Path to uploaded photo
            $table->foreignId('it_staff_id')->nullable()->constrained('users')->onDelete('set null'); // Assigned IT staff
            $table->string('it_photo')->nullable(); // Optional proof of completion photo
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->timestamp('done_at')->nullable(); // When the ticket was completed
            $table->string('public_token')->unique()->nullable(); // For public viewing links
            $table->enum('service_type', ['ticket', 'map_request'])->default('ticket'); // Service type
            $table->string('map_type')->nullable(); // For map requests: kecamatan, kelurahan
            $table->string('zone')->nullable(); // For map requests: WA, WB, WS
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};