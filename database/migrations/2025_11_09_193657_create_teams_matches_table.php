<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teams_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_match')->constrained('matches')->cascadeOnDelete();
            $table->foreignId('id_home_team')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('id_away_team')->constrained('teams')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams_matches');
    }
};
