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
        foreach (['pw_vote_sites', 'pw_shop_items', 'pw_services'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->unsignedSmallInteger('sort_order')->default(0)->after('is_active');
            });
        }
    }

    public function down(): void
    {
        foreach (['pw_vote_sites', 'pw_shop_items', 'pw_services'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
    }
};
