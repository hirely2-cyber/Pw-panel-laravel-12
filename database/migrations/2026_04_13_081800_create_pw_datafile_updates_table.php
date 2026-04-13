<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pw_datafile_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('actor_name', 100);
            $table->string('actor_role', 32)->nullable();
            $table->string('panel_area', 16)->nullable();
            $table->string('target_file', 64);
            $table->string('original_name', 255)->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->text('script_output')->nullable();
            $table->string('status', 16)->default('success');
            $table->timestamps();

            $table->index(['created_at']);
            $table->index(['target_file']);
            $table->index(['status']);
            $table->index(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pw_datafile_updates');
    }
};
