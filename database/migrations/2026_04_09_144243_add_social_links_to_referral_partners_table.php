<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pw_referral_partners', function (Blueprint $table) {
            $table->string('link_tiktok', 255)->nullable()->after('notes');
            $table->string('link_youtube', 255)->nullable()->after('link_tiktok');
            $table->string('link_facebook', 255)->nullable()->after('link_youtube');
        });
    }

    public function down(): void
    {
        Schema::table('pw_referral_partners', function (Blueprint $table) {
            $table->dropColumn(['link_tiktok', 'link_youtube', 'link_facebook']);
        });
    }
};
