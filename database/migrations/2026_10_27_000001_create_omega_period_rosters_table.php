<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('omega_period_rosters', function (Blueprint $table) {
            $table->id();
            // Voting period this team composition starts applying to, e.g. "2026-Q4".
            // It stays in force for later periods until another row overrides it.
            $table->string('period', 20)->unique();
            // Ordered list of team names.
            $table->json('teams');
            // Roster name => list of teams that employee belongs to.
            $table->json('members');
            // The account that last saved this composition (audit trail).
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('omega_period_rosters');
    }
};
