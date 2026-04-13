<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pw_referral_partners', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->unique();
            $table->string('label', 50)->default('Partner');       // Streamer, Content Creator, dll
            $table->unsignedInteger('reward_amount')->default(20000);
            $table->string('reward_type', 10)->default('gold');    // gold | cubi
            $table->unsignedTinyInteger('min_char_level')->default(20);
            $table->unsignedSmallInteger('max_per_day')->default(10);
            $table->unsignedInteger('max_total')->nullable();      // null = unlimited
            $table->boolean('ip_unique_only')->default(true);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('user_id');
        });

        // Add IP tracking to referral rewards
        Schema::table('pw_referral_rewards', function (Blueprint $table) {
            $table->string('referred_ip', 45)->nullable()->after('reward_amount');
        });

        // Store registration IP on users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('register_ip', 45)->nullable()->after('referred_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pw_referral_partners');
        Schema::table('pw_referral_rewards', function (Blueprint $table) {
            $table->dropColumn('referred_ip');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('register_ip');
        });
    }
};
