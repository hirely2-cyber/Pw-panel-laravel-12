<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pw_events', function (Blueprint $table) {
            $table->unsignedInteger('prize_rank1')->default(0)->after('prize_winner_count');
            $table->unsignedInteger('prize_rank2')->default(0)->after('prize_rank1');
            $table->unsignedInteger('prize_rank3')->default(0)->after('prize_rank2');
        });
    }

    public function down(): void
    {
        Schema::table('pw_events', function (Blueprint $table) {
            $table->dropColumn(['prize_rank1', 'prize_rank2', 'prize_rank3']);
        });
    }
};
