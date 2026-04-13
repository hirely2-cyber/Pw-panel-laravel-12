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
        Schema::create('pw_admin_cubi_topups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');       // game user ID
            $table->unsignedBigInteger('admin_id');      // panel user ID yang input
            $table->unsignedInteger('amount');           // jumlah Cubi (standard unit)
            $table->string('reason')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_cubi_topups');
    }
};
