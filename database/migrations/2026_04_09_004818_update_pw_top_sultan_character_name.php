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
        Schema::table('pw_top_sultan', function (Blueprint $table) {
            $table->renameColumn('username', 'character_name');
            $table->dropColumn(['cash_buy', 'cash_sell']);
        });
    }

    public function down(): void
    {
        Schema::table('pw_top_sultan', function (Blueprint $table) {
            $table->renameColumn('character_name', 'username');
            $table->bigInteger('cash_buy')->default(0)->after('cash_add');
            $table->bigInteger('cash_sell')->default(0)->after('cash_buy');
        });
    }
};
