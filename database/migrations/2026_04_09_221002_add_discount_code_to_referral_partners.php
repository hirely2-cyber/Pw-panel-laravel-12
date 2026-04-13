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
        Schema::table('pw_referral_partners', function (Blueprint $table) {
            $table->string('discount_code', 30)->nullable()->unique()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pw_referral_partners', function (Blueprint $table) {
            $table->dropColumn('discount_code');
        });
    }
};
