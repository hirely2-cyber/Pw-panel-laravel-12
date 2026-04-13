<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pw_server_control_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('actor_name', 100)->nullable();
            $table->string('actor_role', 32)->nullable();
            $table->string('panel_area', 16)->nullable();
            $table->string('action', 32);
            $table->string('target_map', 32)->nullable();
            $table->unsignedInteger('delay_seconds')->default(0);
            $table->boolean('result_ok')->default(false);
            $table->text('result_message')->nullable();
            $table->timestamps();

            $table->index(['created_at']);
            $table->index(['action']);
            $table->index(['user_id']);
            $table->index(['panel_area']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pw_server_control_logs');
    }
};
