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

];
