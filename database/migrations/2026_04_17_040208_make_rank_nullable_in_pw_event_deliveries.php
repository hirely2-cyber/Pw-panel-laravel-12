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
        Schema::table('pw_event_deliveries', function (Blueprint $table) {
            $table->unsignedInteger('rank')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('pw_event_deliveries', function (Blueprint $table) {
            $table->unsignedInteger('rank')->nullable(false)->change();
        });
    }
};
