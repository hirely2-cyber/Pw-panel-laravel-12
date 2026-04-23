<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Perfect World Server Settings
    |--------------------------------------------------------------------------
    */
    'server' => [
        'name'        => env('PW_SERVER_NAME', 'Perfect World'),
        'tagline'     => env('PW_SERVER_TAGLINE', 'Private Server'),
        'description' => env('PW_SERVER_DESCRIPTION', 'Private server MMORPG dengan rate tinggi, sistem vote & donate lengkap. Bergabunglah dengan ribuan pemain lainnya!'),
        'ip'          => env('PW_SERVER_IP', '127.0.0.1'),
        'timezone'    => env('PW_TIMEZONE', 'Asia/Jakarta'),
        // PW server version: '156' (1.5.6), '155' (1.5.5), '07' (older)
        'version'     => env('PW_SERVER_VERSION', '156'),
        // Absolute path to PW server root on Linux (trailing slash required)
        // Actual value stored in pw_settings DB table (overrides this default)
        'path'        => env('PW_SERVER_PATH', '/home/pw_server155'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Alokasi character (role_id) per user id
    |--------------------------------------------------------------------------
    | true: char akun di-list dari role_id di [userId .. userId + character_slots - 1]
    | (char pertama = id akun, berikutnya +1 — ketentuan server pvesea). Sumber: MySQL
    | lalu GetUser(3002) hanya id dalam rentang itu. false: pola lama (account_id + cek gamedb).
    */
    'game_account' => [
        'character_role_in_user_id_range' => ! in_array(
            strtolower((string) env('PW_CHAR_RID_IN_USER_RANGE', '1')),
            ['0', 'false', 'off', 'no'],
            true
        ),
        'character_slots' => max(1, min(256, (int) env('PW_CHAR_SLOTS', 16))),
        // Maksimal baris roles dicek per reconcile (setelah sync Tomcat & artisan fix)
        'reconcile_from_gamedb_limit' => max(1, (int) env('PW_RECONCILE_ROLES_LIMIT', 20000)),
    ],

    // pwAdmin (Tomcat) base URL for role sync
    'pwadmin_url' => env('PW_ADMIN_URL', 'http://localhost:8080/pwAdmin'),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    */
    'currency' => [
        'name'          => env('PW_CURRENCY_NAME', 'Gold Points'),
        'icon'          => 'gold-coin.png',
        'rate_idr'      => env('PW_CURRENCY_RATE_IDR', 10000),   // 1 Gold Points = Rp 10.000
        'cubi_rate_idr' => env('PW_CUBI_RATE_IDR', 1000),        // 1 Cubi Gold   = Rp 1.000
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Toggles
    |--------------------------------------------------------------------------
    | Enable/disable panel features without touching code.
    */
    'features' => [
        'shop'     => env('PW_FEATURE_SHOP', true),
        'donate'   => env('PW_FEATURE_DONATE', true),
        'vote'     => env('PW_FEATURE_VOTE', true),
        'voucher'  => env('PW_FEATURE_VOUCHER', true),
        'service'  => env('PW_FEATURE_SERVICE', true),
        'ranking'  => env('PW_FEATURE_RANKING', true),
        'news'     => env('PW_FEATURE_NEWS', true),
        'register' => env('PW_FEATURE_REGISTER', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | PayHook Payment Gateway (QRIS)
    |--------------------------------------------------------------------------
    */
    'payhook' => [
        'url'            => env('PAYHOOK_URL', 'http://localhost:8001'),
        'api_key'        => env('PAYHOOK_API_KEY', ''),
        'webhook_secret' => env('PAYHOOK_WEBHOOK_SECRET', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | GM Panel
    |--------------------------------------------------------------------------
    */
    'gm' => [
        'mail' => env('MAIL_GM', 'admin@perfectworld.local'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Ranking Cache Duration (in minutes)
    |--------------------------------------------------------------------------
    */
    'ranking_cache_minutes' => env('PW_RANKING_CACHE_MINUTES', 30),

    /*
    |--------------------------------------------------------------------------
    | Vote Settings
    |--------------------------------------------------------------------------
    */
    'vote' => [
        'reward_gold'      => env('PW_VOTE_REWARD_GOLD', 5),
        'cooldown_hours'   => env('PW_VOTE_COOLDOWN_HOURS', 24),
    ],

    /*
    |--------------------------------------------------------------------------
    | Referral Settings
    |--------------------------------------------------------------------------
    */
    'referral' => [
        'enabled'                => env('PW_REFERRAL_ENABLED', true),
        'reward_type'            => env('PW_REFERRAL_REWARD_TYPE', 'gold'),
        'reward_gold'            => env('PW_REFERRAL_REWARD_GOLD', 10),
        'min_char_level'         => env('PW_REFERRAL_MIN_CHAR_LEVEL', 1),
        'min_cultivation'        => env('PW_REFERRAL_MIN_CULTIVATION', 0),
        'max_per_day'            => env('PW_REFERRAL_MAX_PER_DAY', 0),
        // Reward tambahan untuk penerima (referred user)
        'referred_reward_type'   => env('PW_REFERRAL_REFERRED_REWARD_TYPE', 'none'),
        'referred_reward_amount' => env('PW_REFERRAL_REFERRED_REWARD_AMOUNT', 0),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cubi Shop Settings
    |--------------------------------------------------------------------------
    */
    'cubi_shop' => [
        'enabled'            => env('PW_CUBI_SHOP_ENABLED', true),
        'discount_percent'   => env('PW_CUBI_SHOP_DISCOUNT_PERCENT', 10),
        'commission_percent' => env('PW_CUBI_SHOP_COMMISSION_PERCENT', 10),
        'min_purchase'       => env('PW_CUBI_SHOP_MIN_PURCHASE', 50000),
        'bonus_multiple'     => env('PW_CUBI_SHOP_BONUS_MULTIPLE', 50),
        'bonus_amount'       => env('PW_CUBI_SHOP_BONUS_AMOUNT', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | ASEAN Currency Rates (1 foreign unit = X IDR)
    |--------------------------------------------------------------------------
    | Display-only conversion for ASEAN cross-border QRIS payments.
    | Backend always processes in IDR. Update rates periodically.
    */
    'currency_rates' => [
        'IDR' => ['rate' => 1,     'symbol' => 'Rp',  'name' => 'Indonesian Rupiah',   'country' => 'id', 'decimals' => 0],
        'MYR' => ['rate' => 3600,  'symbol' => 'RM',  'name' => 'Malaysian Ringgit',   'country' => 'my', 'decimals' => 2],
        'SGD' => ['rate' => 12100, 'symbol' => 'S$',  'name' => 'Singapore Dollar',    'country' => 'sg', 'decimals' => 2],
        'THB' => ['rate' => 460,   'symbol' => '฿',   'name' => 'Thai Baht',           'country' => 'th', 'decimals' => 0],
        'PHP' => ['rate' => 280,   'symbol' => '₱',   'name' => 'Philippine Peso',     'country' => 'ph', 'decimals' => 0],
        'VND' => ['rate' => 0.64,  'symbol' => '₫',   'name' => 'Vietnamese Dong',     'country' => 'vn', 'decimals' => 0],
    ],

];
