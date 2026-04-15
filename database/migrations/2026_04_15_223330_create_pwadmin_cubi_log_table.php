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
        if (!Schema::connection('mysql_game')->hasTable('pwadmin_cubi_log')) {
            Schema::connection('mysql_game')->create('pwadmin_cubi_log', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('userid')->index();
                $table->integer('cash')->default(0);
                $table->datetime('creatime')->useCurrent()->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_game')->dropIfExists('pwadmin_cubi_log');
    }
};
