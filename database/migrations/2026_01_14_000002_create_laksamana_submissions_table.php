<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laksamana_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laksamana_user_id')->nullable()->constrained('laksamana_users')->onDelete('set null');
            $table->foreignId('sbr_business_id')->nullable()->constrained('sbr_businesses')->onDelete('cascade');
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['laksamana_user_id']);
            $table->index(['sbr_business_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('laksamana_submissions');
    }
};