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
        // Add register_req_level to pw_events
        Schema::table('pw_events', function (Blueprint $table) {
            $table->unsignedSmallInteger('register_req_level')->default(50)->after('register_rewards');
        });

        // Track who has received register rewards (anti-duplicate, audit trail)
        Schema::create('event_register_deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedSmallInteger('char_level');
            $table->boolean('distributed')->default(true);
            $table->timestamp('distributed_at')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'user_id']);
            $table->index(['event_id', 'distributed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_register_deliveries');
        Schema::table('pw_events', function (Blueprint $table) {
            $table->dropColumn('register_req_level');
        });
    }
};
