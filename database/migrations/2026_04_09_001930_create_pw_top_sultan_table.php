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
        Schema::create('pw_top_sultan', function (Blueprint $table) {
            $table->unsignedInteger('userid')->primary();
            $table->string('username', 64);
            $table->bigInteger('cash_used')->default(0);
            $table->bigInteger('cash_add')->default(0);
            $table->bigInteger('cash_buy')->default(0);
            $table->bigInteger('cash_sell')->default(0);
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pw_top_sultan');
    }
};
