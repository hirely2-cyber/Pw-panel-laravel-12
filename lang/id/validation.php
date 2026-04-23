<?php

return [
    'custom' => [
        'password' => [
            'regex'    => 'Password tidak boleh mengandung huruf besar dan simbol. Gunakan huruf kecil (a-z) dan angka (0-9) saja.',
            'min'      => 'Password minimal 6 karakter.',
            'max'      => 'Password maksimal 20 karakter.',
            'required' => 'Password wajib diisi.',
            'confirmed'=> 'Konfirmasi password tidak cocok. Pastikan kedua password yang kamu masukkan sama.',
        ],
        'password_confirmation' => [
            'required' => 'Konfirmasi password wajib diisi.',
        ],
    ],
];
