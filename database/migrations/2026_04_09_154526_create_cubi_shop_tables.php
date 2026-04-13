<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cubi Coin packages for sale
        Schema::create('pw_cubi_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedInteger('cubi_amount');
            $table->unsignedBigInteger('price_idr');
            $table->unsignedInteger('bonus_cubi')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Add cubi-shop fields to existing invoices table
        Schema::table('pw_invoices', function (Blueprint $table) {
            $table->string('type', 10)->default('gold')->after('user_id');
            $table->unsignedInteger('cubi_amount')->nullable()->after('bonus_amount');
            $table->string('refcode', 20)->nullable()->after('channel_type');
            $table->unsignedBigInteger('partner_user_id')->nullable()->after('refcode');
            $table->decimal('discount_percent', 5, 2)->nullable()->after('partner_user_id');
            $table->decimal('discount_amount', 15, 2)->nullable()->after('discount_percent');
            $table->decimal('commission_percent', 5, 2)->nullable()->after('discount_amount');
            $table->decimal('commission_amount', 15, 2)->nullable()->after('commission_percent');
            $table->boolean('commission_credited')->default(false)->after('commission_amount');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pw_cubi_packages');

        Schema::table('pw_invoices', function (Blueprint $table) {
            $table->dropColumn([
                'type', 'cubi_amount', 'refcode', 'partner_user_id',
                'discount_percent', 'discount_amount',
                'commission_percent', 'commission_amount', 'commission_credited',
            ]);
        });
    }
};
