<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature   = 'pw:create-admin {name} {password} {email?}';
    protected $description = 'Create an Administrator account in the Perfect World Panel';

    public function handle(): int
    {
        $name  = strtolower($this->argument('name'));
        $pass  = $this->argument('password');
        $email = $this->argument('email') ?? ($name . '@admin.local');

        // Validate input
        if (! preg_match('/^[a-z0-9]+$/', $name)) {
            $this->error('Username hanya boleh huruf kecil dan angka (a-z, 0-9).');
            return self::FAILURE;
        }

        if (strlen($pass) < 6 || ! preg_match('/^[a-z0-9]+$/', $pass)) {
            $this->error('Password minimal 6 karakter, hanya huruf kecil dan angka.');
            return self::FAILURE;
        }

        if (User::where('name', $name)->exists()) {
            $this->error("Username '{$name}' sudah digunakan.");
            return self::FAILURE;
        }

        $hash = Hash::make($name . $pass);
        $id   = User::nextId();

        DB::table('users')->insert([
            'ID'       => $id,
            'name'     => $name,
            'passwd'   => $hash,
            'passwd2'  => $hash,
            'email'    => $email,
            'role'     => 'admin',
        ]);

        $this->info("Admin '{$name}' berhasil dibuat!");
        $this->line("  ID    : {$id}");
        $this->line("  Email : {$email}");
        $this->line("  Role  : admin");

        return self::SUCCESS;
    }
}
