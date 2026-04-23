<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 *
 * Perfect World game server daemon TCP ports.
 * These ports MUST be open/accessible from the web server.
 *
 * gamedbd   : Game database daemon  (character data, roles, inventory)
 * gdeliveryd: Delivery daemon       (mail, ban, mute, territory war)
 * gacd      : Account/cash daemon   (cash/gold management)
 * client    : Client port           (used to check if server is online)
 */

return [

    // Host for gamedbd TCP connection (use 127.0.0.1 when using SSH tunnel)
    'gamedbd_host' => env('PW_GAMEDBD_HOST', env('PW_SERVER_IP', '127.0.0.1')),

    'ports' => [
        'gamedbd'    => env('PW_PORT_GAMEDBD',    29400),
        'gdeliveryd' => env('PW_PORT_GDELIVERYD', 29100),
        'gacd'       => env('PW_PORT_GACD',       29300),
        'client'     => env('PW_PORT_CLIENT',     29000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Socket buffer size (bytes)
    |--------------------------------------------------------------------------
    */
    'maxbuffer' => 65536,

    /*
    |--------------------------------------------------------------------------
    | Socket block mode
    | false = non-blocking (recommended)
    |--------------------------------------------------------------------------
    */
    's_block' => false,

    /*
    |--------------------------------------------------------------------------
    | Socket read type
    | 1 = single recv
    | 2 = loop socket_read 1024 until empty
    | 3 = read header first, then read full length (recommended)
    |--------------------------------------------------------------------------
    */
    's_readtype' => 3,

    /*
    |--------------------------------------------------------------------------
    | Tomcat pwAdmin integration (for Sync Roles)
    |--------------------------------------------------------------------------
    */
    'pwadmin_url'  => env('PW_ADMIN_URL', 'http://127.0.0.1:8080/pwAdmin/'),
    'pwadmin_user' => env('PW_ADMIN_USER', 'admin'),
    'pwadmin_pass' => env('PW_ADMIN_PASS', ''),

    // Same default token as api_sync_roles.jsp (change both if you rotate secrets)
    'pwadmin_api_token' => env('PW_ADMIN_API_TOKEN', 'pw_panel_sync_2026'),

    /*
    | Set true agar setelah api_sync_roles.jsp panel juga memanggil role.jsp?action=sqlsync
    | (sama seperti `php artisan pw:sync-roles`) — di banyak build pwAdmin ini yang mengisi
    | tabel `roles` penuh dari gamedb. false = hanya API JSON.
    */
    'roles_sync_also_sqlsync' => filter_var(
        env('PW_ROLES_SYNC_ALSO_SQLSYNC', '1'),
        FILTER_VALIDATE_BOOL,
    ),

];
