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
        Schema::create('pw_partner_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('channel_name', 100);
            $table->string('platform', 50);
            $table->string('channel_url', 255);
            $table->unsignedInteger('followers_count')->default(0);
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pw_partner_applications');
    }
};
