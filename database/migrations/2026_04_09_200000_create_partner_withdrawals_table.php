<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add payment info columns to partners table
        Schema::table('pw_referral_partners', function (Blueprint $table) {
            $table->string('bank_name', 50)->nullable()->after('link_facebook');
            $table->string('bank_account', 30)->nullable()->after('bank_name');
            $table->string('bank_holder', 100)->nullable()->after('bank_account');
            $table->string('ewallet_type', 30)->nullable()->after('bank_holder');   // Dana, OVO, GoPay, ShopeePay
            $table->string('ewallet_number', 20)->nullable()->after('ewallet_type');
        });

        // Create withdrawals table
        Schema::create('pw_partner_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('amount');           // Amount in IDR
            $table->string('payment_method', 20);           // bank / ewallet
            $table->string('payment_detail', 150);          // e.g. "BCA - 1234567890 (John Doe)"
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pw_partner_withdrawals');

        Schema::table('pw_referral_partners', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'bank_account', 'bank_holder', 'ewallet_type', 'ewallet_number']);
        });
    }
};
