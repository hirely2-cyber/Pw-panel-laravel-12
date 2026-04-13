<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [

            // ── Karakter ──────────────────────────────────────────
            [
                'name'        => 'Ganti Nama Karakter',
                'description' => 'Ubah nama karakter in-game. Nama baru tidak boleh sama dengan karakter lain. Diproses GM dalam 1×24 jam.',
                'type'        => 'karakter',
                'price'       => 50,
                'sort_order'  => 1,
                'is_active'   => true,
            ],
            [
                'name'        => 'Reset Skill Point',
                'description' => 'Mereset semua skill point karakter agar dapat didistribusikan ulang sesuai build yang diinginkan.',
                'type'        => 'karakter',
                'price'       => 20,
                'sort_order'  => 2,
                'is_active'   => true,
            ],
            [
                'name'        => 'Reset Cultivation / Meridian',
                'description' => 'Mereset jalur cultivation atau meridian karakter. Berguna jika ingin mengganti arah pengembangan karakter.',
                'type'        => 'karakter',
                'price'       => 30,
                'sort_order'  => 3,
                'is_active'   => true,
            ],
            [
                'name'        => 'Max Meridian',
                'description' => 'Mengaktifkan seluruh slot meridian karakter secara instan. Hemat waktu grinding panjang.',
                'type'        => 'karakter',
                'price'       => 100,
                'sort_order'  => 4,
                'is_active'   => true,
            ],
            [
                'name'        => 'Reset EXP',
                'description' => 'Mereset Experience Point (EXP) karakter ke 0 pada level saat ini.',
                'type'        => 'karakter',
                'price'       => 0,
                'sort_order'  => 5,
                'is_active'   => true,
            ],
            [
                'name'        => 'Reset SP (Spirit)',
                'description' => 'Mereset Spirit Point (SP) karakter ke 0. Berguna untuk redistribusi spirit.',
                'type'        => 'karakter',
                'price'       => 0,
                'sort_order'  => 6,
                'is_active'   => true,
            ],
            [
                'name'        => 'Level Up Karakter',
                'description' => 'Menaikkan level karakter sebanyak 1 tingkat secara instan. Maksimal sesuai level cap server.',
                'type'        => 'karakter',
                'price'       => 15,
                'sort_order'  => 7,
                'is_active'   => true,
            ],
            [
                'name'        => 'Pindah Guild / Fraksi',
                'description' => 'Memindahkan karakter ke guild atau fraksi lain. Sertakan nama guild tujuan di kolom keterangan.',
                'type'        => 'karakter',
                'price'       => 30,
                'sort_order'  => 8,
                'is_active'   => true,
            ],

            // ── Custom ────────────────────────────────────────────
            [
                'name'        => 'Custom Title / Gelar',
                'description' => 'Memberikan title atau gelar khusus yang tampil di atas nama karakter. Tulis title yang diinginkan di kolom keterangan.',
                'type'        => 'custom',
                'price'       => 75,
                'sort_order'  => 10,
                'is_active'   => true,
            ],
            [
                'name'        => 'Ganti Warna Nama',
                'description' => 'Mengubah warna tampilan nama karakter di in-game. Pilihan warna terbatas sesuai ketersediaan server.',
                'type'        => 'custom',
                'price'       => 50,
                'sort_order'  => 11,
                'is_active'   => false,
            ],
            [
                'name'        => 'Custom Aura',
                'description' => 'Memberikan efek aura permanen pada karakter. Tulis aura yang diinginkan (daftar tersedia di Discord).',
                'type'        => 'custom',
                'price'       => 150,
                'sort_order'  => 12,
                'is_active'   => false,
            ],

            // ── Bantuan ───────────────────────────────────────────
            [
                'name'        => 'Unstuck Karakter',
                'description' => 'Memindahkan karakter yang terjebak, tidak bisa bergerak, atau tersangkut di lokasi bug ke titik spawn default.',
                'type'        => 'bantuan',
                'price'       => 0,
                'sort_order'  => 20,
                'is_active'   => true,
            ],
            [
                'name'        => 'Reset Password Stash',
                'description' => 'Mereset PIN / password stash (gudang) karakter jika lupa. Diverifikasi oleh GM sebelum diproses.',
                'type'        => 'bantuan',
                'price'       => 0,
                'sort_order'  => 21,
                'is_active'   => true,
            ],
            [
                'name'        => 'Teleport ke Lokasi',
                'description' => 'Memindahkan karakter ke lokasi / map tertentu. Berguna jika karakter terjebak di peta yang tidak bisa diakses.',
                'type'        => 'bantuan',
                'price'       => 0,
                'sort_order'  => 22,
                'is_active'   => true,
            ],
            [
                'name'        => 'Pulihkan Item Terhapus',
                'description' => 'Permohonan pemulihan item yang tidak sengaja terhapus atau dijual. Tidak dijamin berhasil, tergantung log server.',
                'type'        => 'bantuan',
                'price'       => 0,
                'sort_order'  => 23,
                'is_active'   => true,
            ],

            // ── Broadcast ─────────────────────────────────────────
            [
                'name'        => 'Broadcast Server',
                'description' => 'Mengirim pesan broadcast yang tampil di seluruh server selama beberapa detik. Maks 100 karakter.',
                'type'        => 'broadcast',
                'price'       => 20,
                'sort_order'  => 30,
                'is_active'   => true,
            ],
        ];

        foreach ($services as $svc) {
            Service::firstOrCreate(['name' => $svc['name']], $svc);
        }
    }
}
