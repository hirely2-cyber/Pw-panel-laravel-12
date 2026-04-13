<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create Perfect World Panel tables.
 * All panel tables use prefix pw_ to avoid collision with game tables.
 */
return new class extends Migration
{
    public function up(): void
    {
        // -------------------------------------------------------
        // News / Articles
        // -------------------------------------------------------
        Schema::create('pw_news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->longText('content');
            $table->string('category', 64)->default('general');
            $table->json('tags')->nullable();
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('author_id');
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();

            $table->index('slug');
            $table->index('category');
            $table->index('is_published');
        });

        // -------------------------------------------------------
        // Shop Items
        // -------------------------------------------------------
        Schema::create('pw_shop_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->longText('description')->nullable();
            $table->string('category', 64)->default('general');
            $table->unsignedBigInteger('item_id')->comment('In-game item ID');
            $table->unsignedInteger('item_count')->default(1);
            $table->decimal('price', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('stock')->nullable()->comment('null = unlimited');
            $table->timestamps();

            $table->index('category');
            $table->index('is_active');
        });

        // -------------------------------------------------------
        // Shop Purchase Logs
        // -------------------------------------------------------
        Schema::create('pw_shop_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('item_id');
            $table->string('item_name');
            $table->decimal('price', 15, 2);
            $table->unsignedInteger('quantity')->default(1);
            $table->string('recipient')->nullable()->comment('Gift target username');
            $table->string('status', 32)->default('success');
            $table->timestamps();

            $table->index('user_id');
        });

        // -------------------------------------------------------
        // PayHook Invoices (Donation payments)
        // -------------------------------------------------------
        Schema::create('pw_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('invoice_number', 64)->unique();
            $table->decimal('amount', 15, 2)->comment('Base amount in IDR');
            $table->smallInteger('unique_suffix')->comment('Unique suffix 1-999');
            $table->decimal('unique_amount', 15, 2)->comment('amount + unique_suffix');
            $table->unsignedBigInteger('gold_amount')->comment('Gold to add after payment');
            $table->unsignedBigInteger('bonus_amount')->default(0);
            $table->string('status', 32)->default('pending');
            $table->string('payment_source')->nullable()->comment('e-wallet source');
            $table->string('payhook_invoice_number')->nullable()->comment('PayHook server invoice ref');
            $table->text('qris_url')->nullable()->comment('QRIS image URL from PayHook');
            $table->json('meta')->nullable()->comment('Raw PayHook webhook payload');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('unique_amount');
        });

        // -------------------------------------------------------
        // Vote Sites
        // -------------------------------------------------------
        Schema::create('pw_vote_sites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('reward')->default(0)->comment('Gold reward per vote');
            $table->unsignedBigInteger('reward_bonus')->default(0);
            $table->unsignedInteger('cooldown')->default(24)->comment('Hours between votes');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('total_votes')->default(0);
            $table->timestamps();
        });

        // -------------------------------------------------------
        // Vote Logs
        // -------------------------------------------------------
        Schema::create('pw_vote_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('site_id');
            $table->string('ip_address', 45);
            $table->unsignedBigInteger('reward_given')->default(0);
            $table->timestamps();

            $table->index('user_id');
            $table->index('site_id');
            $table->index(['user_id', 'site_id']);
        });

        // -------------------------------------------------------
        // Vouchers
        // -------------------------------------------------------
        Schema::create('pw_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 64)->unique();
            $table->string('description')->nullable();
            $table->string('type', 32)->default('gold')->comment('gold | bonus | item');
            $table->unsignedBigInteger('value')->default(0);
            $table->unsignedInteger('max_uses')->nullable()->comment('null = unlimited');
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('code');
            $table->index('is_active');
        });

        // -------------------------------------------------------
        // Voucher Redeem Logs
        // -------------------------------------------------------
        Schema::create('pw_voucher_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('voucher_id');
            $table->unsignedBigInteger('value_received');
            $table->timestamps();

            $table->index('user_id');
            $table->unique(['user_id', 'voucher_id']);
        });

        // -------------------------------------------------------
        // Ingame Services (e.g. rename, unstuck, etc.)
        // -------------------------------------------------------
        Schema::create('pw_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('type', 32)->default('general');
            $table->decimal('price', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('fields')->nullable()->comment('Dynamic form fields config');
            $table->timestamps();
        });

        // -------------------------------------------------------
        // Service Purchase Logs
        // -------------------------------------------------------
        Schema::create('pw_service_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('service_id');
            $table->string('service_name');
            $table->decimal('price', 15, 2);
            $table->json('data')->nullable()->comment('Form submission data');
            $table->string('status', 32)->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
        });

        // -------------------------------------------------------
        // Rankings Cache (synced from game DB)
        // -------------------------------------------------------
        Schema::create('pw_ranking_players', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('character_name', 64);
            $table->string('class', 32)->nullable();
            $table->unsignedBigInteger('level')->default(1);
            $table->unsignedBigInteger('exp')->default(0);
            $table->string('faction_name', 64)->nullable();
            $table->unsignedInteger('pk_points')->default(0);
            $table->unsignedInteger('rank')->default(0);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->index('rank');
            $table->index('level');
        });

        Schema::create('pw_ranking_factions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->unsignedInteger('members_count')->default(0);
            $table->unsignedBigInteger('territory_count')->default(0);
            $table->unsignedInteger('rank')->default(0);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->index('rank');
        });

        // -------------------------------------------------------
        // Password Reset Tokens (separate from game fields)
        // -------------------------------------------------------
        Schema::create('pw_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        $tables = [
            'pw_password_reset_tokens',
            'pw_ranking_factions',
            'pw_ranking_players',
            'pw_service_logs',
            'pw_services',
            'pw_voucher_logs',
            'pw_vouchers',
            'pw_vote_logs',
            'pw_vote_sites',
            'pw_invoices',
            'pw_shop_logs',
            'pw_shop_items',
            'pw_news',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
