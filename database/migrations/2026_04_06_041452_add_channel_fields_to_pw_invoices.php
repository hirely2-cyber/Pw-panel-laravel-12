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
        Schema::table('pw_invoices', function (Blueprint $table) {
            $table->string('channel_type', 32)->nullable()->after('payment_source')->comment('qris, dana, ovo, etc');
            $table->json('payment_instruction')->nullable()->after('channel_type')->comment('account_number, account_name for non-QRIS');
        });
    }

    public function down(): void
    {
        Schema::table('pw_invoices', function (Blueprint $table) {
            $table->dropColumn(['channel_type', 'payment_instruction']);
        });
    }
};
