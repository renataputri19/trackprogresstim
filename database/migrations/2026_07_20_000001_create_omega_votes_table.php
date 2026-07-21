<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('omega_votes', function (Blueprint $table) {
            $table->id();
            // Voting period, e.g. "2026-Q3".
            $table->string('period', 20);
            // The roster name of the employee casting the vote.
            $table->string('voter_name');
            // The team/bidang the vote belongs to.
            $table->string('team');
            // The roster name of the chosen colleague (may equal voter_name).
            $table->string('candidate_name');
            // The authenticated account that submitted the ballot (audit trail).
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // One vote per voter, per team, per period.
            $table->unique(['period', 'voter_name', 'team'], 'omega_votes_unique_ballot');
            $table->index(['period', 'team']);
            $table->index(['period', 'candidate_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('omega_votes');
    }
};
