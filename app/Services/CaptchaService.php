<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Services;

class CaptchaService
{
    private const SESSION_KEY   = 'pw_captcha_answer';
    private const SESSION_CHARS = 'pw_captcha_chars';

    /** Request attribute key used to track per-request verification state. */
    private const REQ_FLAG = '_pw_captcha_verified';

    // Hapus I, O, 0, 1 agar tidak bingung saat dibaca
    private const CHARSET = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    private const COLORS = [
        '#e8b84b', // gold
        '#ff6b6b', // merah
        '#63e2ff', // cyan
        '#7eef8a', // hijau
        '#cf9fff', // ungu
        '#ff9d5c', // oranye
        '#ffdb58', // kuning
        '#ff7eb3', // pink
    ];

    /**
     * Generate captcha teks berwarna, simpan ke session.
     * Returns: array of ['c' => char, 'color' => hex, 'deg' => int]
     */
    public static function generate(): array
    {
        $len = strlen(self::CHARSET);
        $text = '';
        for ($i = 0; $i < 6; $i++) {
            $text .= self::CHARSET[random_int(0, $len - 1)];
        }

        $chars = [];
        $usedColors = [];
        foreach (str_split($text) as $c) {
            // Hindari warna yang sama 2x berturut-turut
            do {
                $color = self::COLORS[array_rand(self::COLORS)];
            } while (end($usedColors) === $color);
            $usedColors[] = $color;

            $chars[] = [
                'c'     => $c,
                'color' => $color,
                'deg'   => random_int(-14, 14),
            ];
        }

        session([
            self::SESSION_KEY   => strtolower($text),
            self::SESSION_CHARS => $chars,
        ]);

        return $chars;
    }

    /**
     * Ambil chars yang sudah di-generate (atau generate baru jika belum ada).
     */
    public static function getChars(): array
    {
        return session(self::SESSION_CHARS) ?? static::generate();
    }

    /**
     * Verify input user (case-insensitive). Hapus session setelah check.
     * Uses a static flag to handle Fortify calling authenticateUsing twice per request.
     */
    public static function verify(string $input): bool
    {
        // Fortify may call authenticateUsing more than once per request.
        // Use request attributes (request-scoped, never leaks between FPM requests).
        if (app('request')->attributes->get(self::REQ_FLAG) === true) {
            return true;
        }

        $expected = session(self::SESSION_KEY);
        session()->forget([self::SESSION_KEY, self::SESSION_CHARS]);

        if ($expected === null) {
            return false;
        }

        $result = strtolower(trim($input)) === $expected;
        app('request')->attributes->set(self::REQ_FLAG, $result);
        return $result;
    }

    /**
     * Refresh captcha — kembalikan chars baru (untuk AJAX).
     */
    public static function refresh(): array
    {
        return static::generate();
    }
}

