<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code', 10)->nullable()->unique()->after('language');
            $table->unsignedInteger('referred_by')->nullable()->after('referral_code');
        });

        Schema::create('pw_referral_rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('referrer_id');
            $table->unsignedInteger('referred_id');
            $table->string('type', 30); // 'registration' = referred user created character
            $table->unsignedBigInteger('reward_amount');
            $table->timestamps();

            $table->index('referrer_id');
            $table->index('referred_id');
            $table->unique(['referrer_id', 'referred_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pw_referral_rewards');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['referral_code', 'referred_by']);
        });
    }
};
