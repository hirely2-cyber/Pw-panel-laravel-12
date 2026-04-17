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
        Schema::table('pw_events', function (Blueprint $table) {

            $table->json('register_rewards')->nullable()->after('referral_tiers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pw_events', function (Blueprint $table) {
            $table->dropColumn('register_rewards');
        });
    }
};
