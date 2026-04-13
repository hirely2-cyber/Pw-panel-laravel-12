<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 *
 * Native TCP client for Perfect World game server daemons.
 * Connects directly to gdeliveryd / gacd — no external package needed.
 */

namespace App\Services;

use Illuminate\Support\Facades\Log;

class GameApiService
{
    protected static string $host;
    protected static int $portDeliver;   // gdeliveryd — mail, ban, mute
    protected static int $portProvider;  // gacd       — world chat
    protected static int $timeout = 5;

    // ─── Protocol opcodes (from config.xml) ───
    const OPCODE_WORLDCHAT    = 133;
    const OPCODE_GMFORBIDROLE = 366;
    const OPCODE_SYSSENDMAIL  = 4214;

    protected static function init(): void
    {
        static::$host         = config('pw-config.server.ip', '127.0.0.1');
        static::$portDeliver  = (int) config('pw-api.ports.gdeliveryd', 29100);
        static::$portProvider = (int) config('pw-api.ports.gacd', 29300);
    }

    // ═══════════════════════════════════════════════════
    //  Binary pack primitives
    // ═══════════════════════════════════════════════════

    protected static function encodeCUint(int $val): string
    {
        if ($val < 0x40)        return chr($val);
        if ($val < 0x4000)      return pack('n', $val | 0x8000);
        if ($val < 0x20000000)  return pack('N', $val | 0xC0000000);
        return chr(0xE0) . pack('N', $val);
    }

    protected static function createHeader(int $opcode, string $payload): string
    {
        return static::encodeCUint($opcode) . static::encodeCUint(strlen($payload)) . $payload;
    }

    /** Pack UTF-8 string as PW Octets (UTF-16LE). */
    protected static function packString(string $text): string
    {
        $utf16 = ($text !== '') ? iconv('UTF-8', 'UTF-16LE', $text) : '';
        return static::encodeCUint(strlen($utf16)) . $utf16;
    }

    /** Pack raw hex octets. */
    protected static function packOctet(string $hex = ''): string
    {
        $bin = ($hex !== '') ? pack('H*', $hex) : '';
        return static::encodeCUint(strlen($bin)) . $bin;
    }

    /** Pack GRoleInventory structure for mail attachments. */
    protected static function packGRoleInventory(array $item): string
    {
        $data  = pack('N', $item['id'] ?? 0);
        $data .= pack('N', $item['pos'] ?? 0);
        $data .= pack('N', $item['count'] ?? 0);
        $data .= pack('N', $item['max_count'] ?? 0);
        $itemOctet = $item['data'] ?? '';
        $binData   = ($itemOctet !== '') ? pack('H*', $itemOctet) : '';
        $data .= static::encodeCUint(strlen($binData)) . $binData;
        $data .= pack('N', $item['proctype'] ?? 0);
        $data .= pack('N', $item['expire_date'] ?? 0);
        $data .= pack('N', $item['guid1'] ?? 0);
        $data .= pack('N', $item['guid2'] ?? 0);
        $data .= pack('N', $item['mask'] ?? 0);
        return $data;
    }

    // ═══════════════════════════════════════════════════
    //  TCP transport
    // ═══════════════════════════════════════════════════

    /**
     * Open TCP socket, send packet, read response.
     *
     * @param bool $recvFirst  gdeliveryd sends a greeting on connect — read it before sending.
     */
    protected static function sendToSocket(string $data, int $port, bool $recvFirst = false): string|false
    {
        $fp = @fsockopen(static::$host, $port, $errno, $errstr, static::$timeout);
        if (!$fp) {
            Log::error("GameApiService: cannot connect to " . static::$host . ":{$port} — {$errstr} ({$errno})");
            return false;
        }

        stream_set_timeout($fp, static::$timeout);

        if ($recvFirst) {
            @fread($fp, 8192);
        }

        fwrite($fp, $data, strlen($data));

        $buf = '';
        while (($chunk = @fread($fp, 1024)) !== false && $chunk !== '') {
            $buf .= $chunk;
            if (strlen($chunk) < 1024) break;
        }

        fclose($fp);
        return $buf;
    }

    protected static function sendToDelivery(string $packet): string|false
    {
        return static::sendToSocket($packet, static::$portDeliver, recvFirst: true);
    }

    protected static function sendToProvider(string $packet): string|false
    {
        return static::sendToSocket($packet, static::$portProvider);
    }

    // ═══════════════════════════════════════════════════
    //  Public API
    // ═══════════════════════════════════════════════════

    /**
     * Send in-game mail to a character.
     * Protocol: SysSendMail (opcode 4214) → gdeliveryd
     */
    public static function sendMail(
        int    $roleId,
        string $title,
        string $message,
        int    $gold = 0,
        array  $item = []
    ): bool {
        static::init();

        if (empty($item)) {
            $item = [
                'id' => 0, 'pos' => 0, 'count' => 0, 'max_count' => 0,
                'data' => '', 'proctype' => 0, 'expire_date' => 0,
                'guid1' => 0, 'guid2' => 0, 'mask' => 0,
            ];
        }

        try {
            // SysSendMail: tid, sysid, sys_type, receiver, title, context, attach_obj, attach_money
            $payload  = pack('N', 344);         // tid  (transaction id)
            $payload .= pack('N', 1025);        // sysid
            $payload .= pack('C', 3);           // sys_type
            $payload .= pack('N', $roleId);     // receiver
            $payload .= static::packString($title);
            $payload .= static::packString($message);
            $payload .= static::packGRoleInventory($item);
            $payload .= pack('N', $gold);       // attach_money

            $result = static::sendToDelivery(
                static::createHeader(self::OPCODE_SYSSENDMAIL, $payload)
            );

            if ($result === false) {
                Log::error("GameApiService::sendMail — connection failed to gdeliveryd");
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('GameApiService::sendMail failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send world-chat broadcast.
     * Protocol: WorldChat (opcode 133) → gacd (provider)
     */
    public static function worldChat(string $message, int $channel = 9, int $roleId = 0): bool
    {
        static::init();

        try {
            // WorldChat: channel, emotion, roleid, name(Octets), msg(Octets), data(Octets)
            $payload  = pack('C', $channel);
            $payload .= pack('C', 0);           // emotion
            $payload .= pack('N', $roleId);
            $payload .= static::packString($message);  // name = chat text (UTF-16LE)
            $payload .= static::packOctet('');          // msg  = empty

            $result = static::sendToProvider(
                static::createHeader(self::OPCODE_WORLDCHAT, $payload)
            );

            if ($result === false) {
                Log::error("GameApiService::worldChat — connection failed to gacd");
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('GameApiService::worldChat failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ban or mute a character/account.
     * Protocol: GMForbidRole (opcode 366) → gdeliveryd
     *
     * @param int    $type   100=ban account, 101=mute account, 0=ban role, 1=mute role
     * @param int    $roleId Target role ID
     * @param int    $time   Duration in seconds (0 = unban/unmute)
     * @param string $reason Reason text
     */
    public static function forbidRole(int $type, int $roleId, int $time, string $reason = ''): bool
    {
        static::init();

        try {
            // GMForbidRole: fbd_type, gmroleid, localsid, dstroleid, forbid_time, reason
            $payload  = pack('C', $type);
            $payload .= pack('N', 0);           // gmroleid  (GM, 0 = system)
            $payload .= pack('N', 0);           // localsid
            $payload .= pack('N', $roleId);
            $payload .= pack('N', $time);
            $payload .= static::packString($reason);

            $result = static::sendToDelivery(
                static::createHeader(self::OPCODE_GMFORBIDROLE, $payload)
            );

            if ($result === false) {
                Log::error("GameApiService::forbidRole — connection failed to gdeliveryd");
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('GameApiService::forbidRole failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if gdeliveryd is reachable.
     */
    public static function isAvailable(): bool
    {
        static::init();
        $fp = @fsockopen(static::$host, static::$portDeliver, $errno, $errstr, 2);
        if ($fp) {
            fclose($fp);
            return true;
        }
        return false;
    }
}
