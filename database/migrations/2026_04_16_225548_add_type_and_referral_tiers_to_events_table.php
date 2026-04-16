<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add event type to pw_events
        Schema::table('pw_events', function (Blueprint $table) {
            $table->string('type', 20)->default('grand_launch')->after('id'); // pre_launch | grand_launch
            $table->json('referral_tiers')->nullable()->after('prize_rank3'); // [{count:10,reward:50},{count:20,reward:100},...]
            $table->unsignedSmallInteger('referral_req_level')->default(50)->after('referral_tiers');
        });

        // Track pre-register referral milestone claims
        Schema::create('pw_referral_milestones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedInteger('user_id');
            $table->unsignedSmallInteger('milestone'); // 10, 20, 30, 50
            $table->unsignedInteger('reward_amount');   // Cubi amount
            $table->boolean('distributed')->default(false);
            $table->timestamp('distributed_at')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'user_id', 'milestone']);
            $table->foreign('event_id')->references('id')->on('pw_events')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pw_referral_milestones');

        Schema::table('pw_events', function (Blueprint $table) {
            $table->dropColumn(['type', 'referral_tiers', 'referral_req_level']);
        });
    }
};
