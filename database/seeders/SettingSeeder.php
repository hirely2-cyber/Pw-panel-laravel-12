<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Site identity
            ['key' => 'site_logo',        'value' => null,              'group' => 'identity'],
            ['key' => 'site_hero_bg',     'value' => null,              'group' => 'identity'],

            // Social links
            ['key' => 'social_whatsapp',  'value' => null,              'group' => 'social'],
            ['key' => 'social_facebook',  'value' => null,              'group' => 'social'],
            ['key' => 'social_discord',   'value' => null,              'group' => 'social'],

            // Game client
            ['key' => 'server_version',   'value' => '1.5.5',           'group' => 'game'],
            ['key' => 'download_url',     'value' => null,              'group' => 'game'],
        ];

        foreach ($defaults as $row) {
            Setting::updateOrCreate(
                ['key' => $row['key']],
                ['value' => $row['value'], 'group' => $row['group']]
            );
        }
    }
}
