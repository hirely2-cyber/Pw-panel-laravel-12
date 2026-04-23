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
        // Poll session
        Schema::create('pw_vote_polls', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Dungeon Voting');
            $table->boolean('is_active')->default(false);
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });

        // Options (dungeons) in each poll
        Schema::create('pw_vote_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained('pw_vote_polls')->cascadeOnDelete();
            $table->string('map_id', 20);    // e.g. is25
            $table->string('map_name', 100); // e.g. Warsong City
            $table->unsignedInteger('votes')->default(0);
            $table->timestamps();
        });

        // Log per-IP votes to prevent duplicate
        Schema::create('pw_vote_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained('pw_vote_polls')->cascadeOnDelete();
            $table->string('voter_ip', 45);
            $table->string('map_id', 20);
            $table->timestamp('voted_at')->useCurrent();
            $table->unique(['poll_id', 'voter_ip']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pw_vote_logs');
        Schema::dropIfExists('pw_vote_options');
        Schema::dropIfExists('pw_vote_polls');
    }
};
