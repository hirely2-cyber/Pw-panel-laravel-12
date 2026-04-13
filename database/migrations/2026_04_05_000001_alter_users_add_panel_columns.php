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
 * Alter existing game `users` table.
 * The `users` table belongs to the Perfect World game server.
 * We only ADD panel-specific columns, never remove game columns.
 *
 * Existing game columns (DO NOT TOUCH):
 *   ID, name, passwd, passwd2, Prompt, answer, truename, idnumber,
 *   email, mobilenumber, province, city, phonenumber, address,
 *   postalcode, gender, birthday, creatime, qq
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Panel role: Administrator | Gamemaster | Player
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role', 32)->default('Player')->after('qq');
            }

            // Panel balance (in-game gold/coin managed by panel)
            if (! Schema::hasColumn('users', 'money')) {
                $table->bigInteger('money')->default(0)->after('role');
            }

            // Bonus points from donations/votes
            if (! Schema::hasColumn('users', 'bonuses')) {
                $table->bigInteger('bonuses')->default(0)->after('money');
            }

            // Preferred language (id / en)
            if (! Schema::hasColumn('users', 'language')) {
                $table->string('language', 10)->default('id')->after('bonuses');
            }

            // Laravel auth
            if (! Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('language');
            }

            if (! Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken()->after('email_verified_at');
            }

            // Two-Factor Authentication (Fortify)
            if (! Schema::hasColumn('users', 'two_factor_secret')) {
                $table->text('two_factor_secret')->nullable()->after('remember_token');
            }

            if (! Schema::hasColumn('users', 'two_factor_recovery_codes')) {
                $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            }

            if (! Schema::hasColumn('users', 'two_factor_confirmed_at')) {
                $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            }

            // Profile photo
            if (! Schema::hasColumn('users', 'profile_photo_path')) {
                $table->string('profile_photo_path', 2048)->nullable()->after('two_factor_confirmed_at');
            }

            // Last activity for online tracking
            if (! Schema::hasColumn('users', 'last_active_at')) {
                $table->timestamp('last_active_at')->nullable()->after('profile_photo_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'role', 'money', 'bonuses', 'language',
                'email_verified_at', 'remember_token',
                'two_factor_secret', 'two_factor_recovery_codes',
                'two_factor_confirmed_at', 'profile_photo_path',
                'last_active_at',
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
