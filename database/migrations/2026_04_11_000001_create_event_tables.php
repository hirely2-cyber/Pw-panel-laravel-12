<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pw_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('req_level')->default(105);
            $table->unsignedSmallInteger('req_cultivation')->default(22);
            $table->unsignedInteger('prize_total_cubi')->default(20000);
            $table->unsignedInteger('prize_winner_count')->default(50);
            $table->enum('status', ['draft', 'active', 'ended', 'distributed'])->default('draft');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pw_event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('pw_events')->cascadeOnDelete();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('role_id');
            $table->string('character_name', 64);
            $table->string('class', 32)->nullable();
            $table->unsignedSmallInteger('level')->default(1);
            $table->unsignedSmallInteger('cultivation')->default(0);
            $table->string('cultivation_label', 32)->nullable();
            $table->timestamp('qualified_at')->nullable();
            $table->boolean('prize_distributed')->default(false);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'role_id']);
            $table->index(['event_id', 'qualified_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pw_event_participants');
        Schema::dropIfExists('pw_events');
    }
};
