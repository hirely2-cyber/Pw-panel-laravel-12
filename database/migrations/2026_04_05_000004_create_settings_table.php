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

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pw_settings', function (Blueprint $table) {
            $table->string('key', 128)->primary();
            $table->text('value')->nullable();
            $table->string('group', 64)->default('general');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pw_settings');
    }
};
