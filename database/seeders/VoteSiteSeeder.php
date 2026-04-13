<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace Database\Seeders;

use App\Models\VoteSite;
use Illuminate\Database\Seeder;

class VoteSiteSeeder extends Seeder
{
    public function run(): void
    {
        $sites = [
            [
                'name'      => 'Top100Arena',
                'url'       => 'https://www.top100arena.com/in/?ID=0',
                'reward'    => 5,
                'cooldown'  => 24,
                'is_active' => true,
            ],
            [
                'name'      => 'MMOTop100',
                'url'       => 'https://www.mmotop100.com/in/?ID=0',
                'reward'    => 5,
                'cooldown'  => 24,
                'is_active' => true,
            ],
            [
                'name'      => 'GTOP100',
                'url'       => 'https://www.gtop100.com/in/?ID=0',
                'reward'    => 5,
                'cooldown'  => 24,
                'is_active' => true,
            ],
        ];

        foreach ($sites as $site) {
            VoteSite::firstOrCreate(['name' => $site['name']], $site);
        }
    }
}
