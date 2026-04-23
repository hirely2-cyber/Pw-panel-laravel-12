<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 * 
 * GameDbService — Direct TCP binary communication with PW gamedbd.
 *
 * Reads character data (coins, reputation, SP, HP/MP, attributes, etc.)
 * by speaking the PW binary protocol over TCP to gamedbd port 29400.
 *
 * Based on the iWeb (phpiweb) implementation's stream/GRole/character
 * binary protocol specification for PW v1.5.5+.
 */

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GameDbService
{
    protected string $host;
    protected int $port;
    protected int $timeout;

    /** Binary read state */
    protected string $readBuf = '';
    protected int $readPos = 0;

    /** Binary write state */
    protected string $writeBuf = '';

    /** Persistent TCP connection */
    protected $connection = null;
    protected bool $connectionFailed = false;

    // ── Opcodes ──────────────────────────────────────
    const OP_GET_ROLE = 0x1F43;
    const OP_GET_USER = 3002;
    const OP_DB_MODIFY_ROLE = 8005;
    const OP_PUT_ROLE_STATUS = 3014;

    // Bitmask for DBModifyRoleData
    const MASK_POCKET_MONEY = 4;
    const MASK_STORE_MONEY  = 8;

    public function __construct()
    {
        $this->host    = config('pw-api.gamedbd_host', config('pw-config.server.ip', '127.0.0.1'));
        $this->port    = (int) config('pw-api.ports.gamedbd', 29400);
        $this->timeout = 2;
    }

    public function __destruct()
    {
        $this->closeConnection();
    }

    /**
     * Fetch parsed character data for a single role.
     * Returns null on failure. Caches for 120 seconds.
     */
    public function getRoleData(int $roleId): ?array
    {
        return Cache::remember("pw.role.{$roleId}", 120, function () use ($roleId) {
            try {
                return $this->fetchRoleData($roleId);
            } catch (\Throwable $e) {
                Log::warning("GameDbService::getRoleData({$roleId}) failed: " . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Fetch role data for multiple role IDs at once.
     */
    public function getRolesData(array $roleIds): array
    {
        $results = [];
        foreach ($roleIds as $id) {
            $results[$id] = $this->getRoleData($id);
        }
        return $results;
    }

    /**
     * GetUser (3002) full parse: role_ids + cash fields. Caches 60s.
     * Source of truth for which role_ids belong to an account; MySQL `roles.account_id` can be wrong.
     */
    public function getUserDataFromGamedb(int $userId, bool $useCache = true): ?array
    {
        if (! $useCache) {
            try {
                return $this->fetchGetUser($userId);
            } catch (\Throwable $e) {
                Log::warning("GameDbService::getUserDataFromGamedb({$userId}): " . $e->getMessage());

                return null;
            }
        }

        return Cache::remember("pw.getuser.{$userId}", 60, function () use ($userId) {
            try {
                return $this->fetchGetUser($userId);
            } catch (\Throwable $e) {
                Log::warning("GameDbService::getUserDataFromGamedb({$userId}): " . $e->getMessage());

                return null;
            }
        });
    }

    /**
     * Get real-time Cubi (cash) balance for an account from gamedbd.
     * Returns array with cash, money, cash_add, cash_buy, cash_sell, cash_used.
     * Returns null on failure. Caches for 60 seconds (shared with getUserDataFromGamedb).
     */
    public function getUserCash(int $userId, bool $useCache = true): ?array
    {
        $d = $this->getUserDataFromGamedb($userId, $useCache);
        if ($d === null) {
            return null;
        }

        return array_intersect_key(
            $d,
            array_flip(['logicuid', 'cash', 'money', 'cash_add', 'cash_buy', 'cash_sell', 'cash_used'])
        );
    }

    /**
     * Fetch User data from gamedbd via GetUser RPC (opcode 3002).
     * UserArg: id (int32), login_time (int32), login_ip (int32)
     * User: logicuid, rolelist (GNET int32 vector, CUint n + n×int32), then cash & money fields.
     */
    protected function fetchGetUser(int $userId): ?array
    {
        $this->writeBuf = '';
        $this->writeInt32(-1);       // local session id
        $this->writeInt32($userId);  // UserArg.id
        $this->writeInt32(0);        // UserArg.login_time
        $this->writeInt32(0);        // UserArg.login_ip

        $payload = $this->writeBuf;
        $packet  = $this->encodeCUint(self::OP_GET_USER)
                 . $this->encodeCUint(strlen($payload))
                 . $payload;

        $response = $this->tcpSend($packet);
        if ($response === null || strlen($response) < 10) {
            return null;
        }

        $this->readBuf = $response;
        $this->readPos = 0;

        $respOpcode = $this->readCUint();
        $respLen    = $this->readCUint();
        $localSid   = $this->readInt32();
        $retCode    = $this->readInt32();

        if ($retCode !== 0) {
            Log::debug("GetUser retcode={$retCode} for userId={$userId}");

            return null;
        }

        try {
            $logicuid = $this->readInt32();
            $roleIds  = $this->readInt32Vector();

            $cash      = $this->readInt32();
            $money     = $this->readInt32();
            $cash_add  = $this->readInt32();
            $cash_buy  = $this->readInt32();
            $cash_sell = $this->readInt32();
            $cash_used = $this->readInt32();

            return [
                'logicuid'  => $logicuid,
                'role_ids'  => $roleIds,
                'cash'      => $cash,
                'money'     => $money,
                'cash_add'  => $cash_add,
                'cash_buy'  => $cash_buy,
                'cash_sell' => $cash_sell,
                'cash_used' => $cash_used,
            ];
        } catch (\Throwable $e) {
            Log::warning("GameDbService::fetchGetUser parse userId={$userId}: " . $e->getMessage());

            return null;
        }
    }

    // ═══════════════════════════════════════════════════
    //  Protocol: fetch & parse
    // ═══════════════════════════════════════════════════

    protected function fetchRoleData(int $roleId): ?array
    {
        // Build request payload
        $this->writeBuf = '';
        $this->writeInt32(-1);
        $this->writeInt32($roleId);

        // Wrap payload with opcode header
        $payload = $this->writeBuf;
        $packet  = $this->encodeCUint(self::OP_GET_ROLE)
                 . $this->encodeCUint(strlen($payload))
                 . $payload;

        // Send & receive
        $response = $this->tcpSend($packet);
        if ($response === null || strlen($response) < 10) {
            return null;
        }

        // Parse response
        $this->readBuf = $response;
        $this->readPos = 0;

        $respOpcode = $this->readCUint();
        $respLen    = $this->readCUint();
        $localSid   = $this->readInt32();
        $retCode    = $this->readInt32();

        if (strlen($this->readBuf) - $this->readPos < 4) {
            return null; // empty response
        }

        return $this->parseRoleData();
    }

    /**
     * Parse the full role data following PW v1.5.5_156 structure.
     */
    protected function parseRoleData(): ?array
    {
        $data = [];

        try {
            // ── Section: base ──
            $data['base'] = $this->parseBase();

            // ── Section: status ──
            $statusStart = $this->readPos;
            $data['status'] = $this->parseStatus();
            $data['_raw_status'] = substr($this->readBuf, $statusStart, $this->readPos - $statusStart);

            // ── Section: pocket ──
            $data['pocket'] = $this->parsePocket();

            // ── Section: equipment (skip) ──
            $data['equipment'] = $this->parseEquipment();

            // ── Section: storehouse ──
            $data['storehouse'] = $this->parseStorehouse();

        } catch (\Throwable $e) {
            Log::debug("GameDbService parse error at pos {$this->readPos}: " . $e->getMessage());
        }

        return $data;
    }

    protected function parseBase(): array
    {
        $base = [];
        $base['version']    = $this->readByte();
        $base['id']         = $this->readInt32();
        $base['name']       = $this->readString();
        $base['race']       = $this->readInt32();
        $base['cls']        = $this->readInt32();
        $base['gender']     = $this->readByte();
        $base['custom_data']= $this->readOctets();
        $base['config_data']= $this->readOctets();
        $base['custom_stamp']= $this->readInt32();
        $base['status']     = $this->readByte();
        $base['delete_time']= $this->readInt32();
        $base['create_time']= $this->readInt32();
        $base['lastlogin']  = $this->readInt32();

        // forbid array
        $forbidCount = $this->readCUint();
        for ($i = 0; $i < $forbidCount; $i++) {
            $this->readInt32(); // type
            $this->readInt32(); // time
            $this->readInt32(); // create_time
            $this->readOctets(); // reason
        }

        $base['help_states'] = $this->readOctets();
        $base['spouse']      = $this->readInt32();
        $base['userid']      = $this->readInt32();
        $base['cross_data']  = $this->readOctets();
        $base['reserved2']   = $this->readOctets();
        $base['reserved3']   = $this->readOctets();
        $base['reserved4']   = $this->readOctets();

        return $base;
    }

    protected function parseStatus(): array
    {
        $s = [];
        $s['version']       = $this->readByte();
        $s['level']         = $this->readInt32();
        $s['cultivation']   = $this->readInt32(); // level2
        $s['exp']           = $this->readInt32();
        $s['sp']            = $this->readInt32();  // Spirit
        $s['pp']            = $this->readInt32();  // free attribute points
        $s['hp']            = $this->readInt32();
        $s['mp']            = $this->readInt32();
        $s['pos_x']         = $this->readFloat();
        $s['pos_y']         = $this->readFloat();
        $s['pos_z']         = $this->readFloat();
        $s['world_tag']     = $this->readInt32();
        $s['invader_state'] = $this->readInt32();
        $s['invader_time']  = $this->readInt32();
        $s['pariah_time']   = $this->readInt32();
        $s['reputation']    = $this->readInt32();
        $s['custom_status'] = $this->readOctets();
        $s['filter_data']   = $this->readOctets();
        $s['charactermode'] = $this->readOctets();
        $s['instancekeylist']= $this->readOctets();
        $s['dbltime_expire']= $this->readInt32();
        $s['dbltime_mode']  = $this->readInt32();
        $s['dbltime_begin'] = $this->readInt32();
        $s['dbltime_used']  = $this->readInt32();
        $s['dbltime_max']   = $this->readInt32();
        $s['time_used']     = $this->readInt32();
        $s['dbltime_data']  = $this->readOctets();
        $s['storesize']     = $this->readInt16();
        $s['petcorral']     = $this->readOctets();

        // property octets (contains extend_prop with stats)
        $propertyOctets = $this->readOctets();
        $s['property']  = $this->parseProperty($propertyOctets);

        // var_data octets
        $this->readOctets(); // var_data — skip for now

        $s['skills']            = $this->readOctets();
        $s['storehousepasswd']  = $this->readOctets();
        $s['waypointlist']      = $this->readOctets();
        $s['coolingtime']       = $this->readOctets();
        $s['npc_relation']      = $this->readOctets();
        $s['multi_exp_ctrl']    = $this->readOctets();
        $s['storage_task']      = $this->readOctets();
        $s['faction_contrib']   = $this->readOctets();
        $s['force_data']        = $this->readOctets();
        $s['online_award']      = $this->readOctets();
        $s['profit_time_data']  = $this->readOctets();
        $s['country_data']      = $this->readOctets();
        $s['king_data']         = $this->readOctets();
        $s['meridian_data']     = $this->readOctets();
        $s['extraprop']         = $this->readOctets();
        $s['title_data']        = $this->readOctets();
        $s['reincarnation_data']= $this->readOctets();
        $s['realm_data']        = $this->readOctets();
        $s['reserved2']         = $this->readByte();
        $s['reserved3']         = $this->readByte();

        return $s;
    }

    protected function parsePocket(): array
    {
        $p = [];
        $p['capacity']  = $this->readInt32();
        $p['timestamp'] = $this->readInt32();
        $p['money']     = $this->readInt32();

        $invCount = $this->readCUint();
        $p['items'] = [];
        for ($i = 0; $i < $invCount; $i++) {
            $p['items'][] = $this->readItem();
        }

        $p['reserved6'] = $this->readInt32();
        $p['reserved7'] = $this->readInt32();

        return $p;
    }

    protected function parseEquipment(): array
    {
        $eqpCount = $this->readCUint();
        $items = [];
        for ($i = 0; $i < $eqpCount; $i++) {
            $items[] = $this->readItem();
        }
        return ['count' => $eqpCount, 'items' => $items];
    }

    protected function parseStorehouse(): array
    {
        $sh = [];
        $sh['capacity'] = $this->readInt32();
        $sh['money']    = $this->readInt32();

        $storeCount = $this->readCUint();
        $sh['items'] = [];
        for ($i = 0; $i < $storeCount; $i++) {
            $sh['items'][] = $this->readItem();
        }

        $sh['size1'] = $this->readByte();
        $sh['size2'] = $this->readByte();

        $dressCount = $this->readCUint();
        $sh['fashion'] = [];
        for ($i = 0; $i < $dressCount; $i++) {
            $sh['fashion'][] = $this->readItem();
        }

        $matCount = $this->readCUint();
        $sh['material'] = [];
        for ($i = 0; $i < $matCount; $i++) {
            $sh['material'][] = $this->readItem();
        }

        $sh['size3'] = $this->readByte();

        $cardCount = $this->readCUint();
        $sh['cards'] = [];
        for ($i = 0; $i < $cardCount; $i++) {
            $sh['cards'][] = $this->readItem();
        }

        $sh['reserved'] = $this->readInt16();

        return $sh;
    }

    /**
     * Read a single inventory item and return its fields.
     */
    protected function readItem(): array
    {
        return [
            'id'          => $this->readInt32(),
            'pos'         => $this->readInt32(),
            'count'       => $this->readInt32(),
            'max_count'   => $this->readInt32(),
            'data'        => bin2hex($this->readOctets()),
            'proctype'    => $this->readInt32(),
            'expire_date' => $this->readInt32(),
            'guid1'       => $this->readInt32(),
            'guid2'       => $this->readInt32(),
            'mask'        => $this->readInt32(),
        ];
    }

    /**
     * Parse the extend_prop (property) octets for stats.
     * NOTE: Property octets use little-endian byte order (int32sm / float-sm).
     */
    protected function parseProperty(string $octets): array
    {
        if (strlen($octets) < 60) {
            return [];
        }

        // Save and swap read buffer
        $savedBuf = $this->readBuf;
        $savedPos = $this->readPos;
        $this->readBuf = $octets;
        $this->readPos = 0;

        $prop = [];
        $prop['vitality']   = $this->readInt32LE(); // CON
        $prop['energy']     = $this->readInt32LE(); // INT
        $prop['strength']   = $this->readInt32LE(); // STR
        $prop['agility']    = $this->readInt32LE(); // AGI
        $prop['max_hp']     = $this->readInt32LE();
        $prop['max_mp']     = $this->readInt32LE();
        $prop['hp_gen']     = $this->readInt32LE();
        $prop['mp_gen']     = $this->readInt32LE();

        // Speeds (4 floats, little-endian)
        $prop['walk_speed']   = $this->readFloatLE();
        $prop['run_speed']    = $this->readFloatLE();
        $prop['swim_speed']   = $this->readFloatLE();
        $prop['flight_speed'] = $this->readFloatLE();

        $prop['attack']      = $this->readInt32LE();
        $prop['damage_low']  = $this->readInt32LE(); // P-Atk min
        $prop['damage_high'] = $this->readInt32LE(); // P-Atk max
        $prop['attack_speed']= $this->readInt32LE();
        $prop['attack_range']= $this->readFloatLE();

        // Elemental damage (5 elements × 2: low/high)
        for ($i = 0; $i < 5; $i++) {
            $prop["addon_damage_low_{$i}"] = $this->readInt32LE();
        }
        for ($i = 0; $i < 5; $i++) {
            $prop["addon_damage_high_{$i}"] = $this->readInt32LE();
        }

        $prop['damage_magic_low']  = $this->readInt32LE(); // M-Atk min
        $prop['damage_magic_high'] = $this->readInt32LE(); // M-Atk max

        // Elemental resistance (5 elements)
        for ($i = 0; $i < 5; $i++) {
            $prop["resistance_{$i}"] = $this->readInt32LE();
        }

        $prop['defense'] = $this->readInt32LE(); // P-Def
        $prop['armor']   = $this->readInt32LE();
        $prop['max_ap']  = $this->readInt32LE(); // Vigor

        // Restore buffer
        $this->readBuf = $savedBuf;
        $this->readPos = $savedPos;

        return $prop;
    }

    // ═══════════════════════════════════════════════════
    //  Binary primitives
    // ═══════════════════════════════════════════════════

    protected function readByte(): int
    {
        $v = ord($this->readBuf[$this->readPos]);
        $this->readPos += 1;
        return $v;
    }

    /**
     * Read big-endian signed 32-bit integer.
     */
    protected function readInt32(): int
    {
        $raw = substr($this->readBuf, $this->readPos, 4);
        $this->readPos += 4;
        // Big-endian to native: reverse then unpack as native int
        $val = unpack('i', strrev($raw));
        return $val[1];
    }

    /**
     * Read big-endian unsigned 16-bit integer.
     */
    protected function readInt16(): int
    {
        $raw = substr($this->readBuf, $this->readPos, 2);
        $this->readPos += 2;
        $val = unpack('n', $raw);
        return $val[1];
    }

    /**
     * Read big-endian float.
     */
    protected function readFloat(): float
    {
        $raw = substr($this->readBuf, $this->readPos, 4);
        $this->readPos += 4;
        $val = unpack('f', strrev($raw));
        return $val[1];
    }

    /**
     * Read little-endian signed 32-bit integer (for property/addon octets).
     */
    protected function readInt32LE(): int
    {
        $raw = substr($this->readBuf, $this->readPos, 4);
        $this->readPos += 4;
        $val = unpack('l', $raw); // native little-endian on x86
        return $val[1];
    }

    /**
     * Read little-endian float (for property/addon octets).
     */
    protected function readFloatLE(): float
    {
        $raw = substr($this->readBuf, $this->readPos, 4);
        $this->readPos += 4;
        $val = unpack('f', $raw); // native little-endian on x86
        return $val[1];
    }

    /**
     * GNET vector<int> — CUint count then N int32.
     */
    protected function readInt32Vector(): array
    {
        $n = $this->readCUint();
        if ($n < 0 || $n > 256) {
            throw new \RuntimeException("getUser: invalid int32vector length: {$n}");
        }
        $out = [];
        for ($i = 0; $i < $n; $i++) {
            $out[] = $this->readInt32();
        }

        return $out;
    }

    /**
     * Read PW CUint (compact unsigned integer).
     */
    protected function readCUint(): int
    {
        $b = ord($this->readBuf[$this->readPos]);
        $top = $b & 0xE0;

        if ($top === 0xE0) {
            $this->readPos += 1;
            $raw = substr($this->readBuf, $this->readPos, 4);
            $this->readPos += 4;
            $val = unpack('N', $raw);
            return $val[1];
        }
        if (($b & 0xC0) === 0xC0) {
            $raw = substr($this->readBuf, $this->readPos, 4);
            $this->readPos += 4;
            $val = unpack('N', $raw);
            return $val[1] & 0x3FFFFFFF;
        }
        if (($b & 0x80) === 0x80) {
            $raw = substr($this->readBuf, $this->readPos, 2);
            $this->readPos += 2;
            $val = unpack('n', $raw);
            return $val[1] & 0x7FFF;
        }

        $this->readPos += 1;
        return $b;
    }

    /**
     * Read octets (cuint length + raw bytes).
     */
    protected function readOctets(): string
    {
        $len = $this->readCUint();
        if ($len <= 0) return '';
        $data = substr($this->readBuf, $this->readPos, $len);
        $this->readPos += $len;
        return $data;
    }

    /**
     * Read PW string (cuint length + UTF-16LE data → UTF-8).
     */
    protected function readString(): string
    {
        $octets = $this->readOctets();
        if ($octets === '') return '';
        $converted = @iconv('UTF-16LE', 'UTF-8', $octets);
        return $converted !== false ? $converted : '';
    }

    // ── Write primitives ──

    protected function writeInt32(int $val): void
    {
        $this->writeBuf .= strrev(pack('i', $val));
    }

    /**
     * Encode a CUint value to bytes.
     */
    protected function encodeCUint(int $val): string
    {
        if ($val < 0x40) {
            return chr($val);
        }
        if ($val < 0x4000) {
            return pack('n', $val | 0x8000);
        }
        if ($val < 0x20000000) {
            return pack('N', $val | 0xC0000000);
        }
        return chr(0xE0) . pack('N', $val);
    }
    protected function writeFloat(float $val): void
    {
        $this->writeBuf .= strrev(pack('f', $val));
    }

    protected function writeInt64(int $val): void
    {
        $this->writeBuf .= pack('J', $val);
    }

    protected function writeByte(int $val): void
    {
        $this->writeBuf .= chr($val & 0xFF);
    }

    protected function writeInt16(int $val): void
    {
        $this->writeBuf .= pack('n', $val);
    }

    protected function writeOctets(string $data): void
    {
        $this->writeBuf .= $this->encodeCUint(strlen($data));
        $this->writeBuf .= $data;
    }

    // ═══════════════════════════════════════════════════
    //  TCP transport
    // ═══════════════════════════════════════════════════

    protected function tcpSend(string $packet): ?string
    {
        // Fail fast if a previous connection attempt failed
        if ($this->connectionFailed) {
            return null;
        }

        try {
            $fp = $this->getConnection();
        } catch (\RuntimeException $e) {
            $this->connectionFailed = true;
            throw $e;
        }

        fwrite($fp, $packet);

        // Protocol-aware read: first read header (opcode CUint + length CUint)
        // then read exactly 'length' bytes of payload.
        // CUint max header size is 5+5 = 10 bytes. Read enough for the header first.
        $header = $this->tcpReadBytes($fp, 2);
        if ($header === null) {
            $this->closeConnection();
            return null;
        }

        // Decode opcode CUint to find its byte length
        $opcodeLen = $this->cuintByteLen(ord($header[0]));
        if ($opcodeLen > 2) {
            $extra = $this->tcpReadBytes($fp, $opcodeLen - 2);
            if ($extra === null) { $this->closeConnection(); return null; }
            $header .= $extra;
        }

        // Now read the length CUint (starts at offset $opcodeLen)
        if (strlen($header) < $opcodeLen + 1) {
            $extra = $this->tcpReadBytes($fp, $opcodeLen + 1 - strlen($header));
            if ($extra === null) { $this->closeConnection(); return null; }
            $header .= $extra;
        }

        $lenFirstByte = ord($header[$opcodeLen]);
        $lenCuintLen = $this->cuintByteLen($lenFirstByte);

        // Ensure we have the full length CUint
        $headerNeeded = $opcodeLen + $lenCuintLen;
        if (strlen($header) < $headerNeeded) {
            $extra = $this->tcpReadBytes($fp, $headerNeeded - strlen($header));
            if ($extra === null) { $this->closeConnection(); return null; }
            $header .= $extra;
        }

        // Decode the payload length
        $payloadLen = $this->decodeCUintAt($header, $opcodeLen);

        // Read exactly payloadLen bytes
        if ($payloadLen > 0 && $payloadLen < 204800) {
            $payload = $this->tcpReadBytes($fp, $payloadLen);
            if ($payload === null) { $this->closeConnection(); return null; }
            return $header . $payload;
        }

        return $header;
    }

    /**
     * Read exactly $n bytes from a stream.
     */
    protected function tcpReadBytes($fp, int $n): ?string
    {
        $data = '';
        $remaining = $n;
        while ($remaining > 0) {
            $chunk = fread($fp, $remaining);
            if ($chunk === false || $chunk === '') {
                return null;
            }
            $data .= $chunk;
            $remaining -= strlen($chunk);
        }
        return $data;
    }

    /**
     * Determine byte length of a CUint from its first byte.
     */
    protected function cuintByteLen(int $firstByte): int
    {
        if (($firstByte & 0xE0) === 0xE0) return 5;
        if (($firstByte & 0xC0) === 0xC0) return 4;
        if (($firstByte & 0x80) === 0x80) return 2;
        return 1;
    }

    /**
     * Decode CUint value at a given offset in a buffer.
     */
    protected function decodeCUintAt(string $buf, int $offset): int
    {
        $b = ord($buf[$offset]);
        if (($b & 0xE0) === 0xE0) {
            $raw = substr($buf, $offset + 1, 4);
            return unpack('N', $raw)[1];
        }
        if (($b & 0xC0) === 0xC0) {
            $raw = substr($buf, $offset, 4);
            return unpack('N', $raw)[1] & 0x3FFFFFFF;
        }
        if (($b & 0x80) === 0x80) {
            $raw = substr($buf, $offset, 2);
            return unpack('n', $raw)[1] & 0x7FFF;
        }
        return $b;
    }

    /**
     * Get or create persistent TCP connection.
     */
    protected function getConnection()
    {
        if ($this->connection && !feof($this->connection)) {
            return $this->connection;
        }

        $fp = @fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
        if (!$fp) {
            throw new \RuntimeException("Cannot connect to gamedbd at {$this->host}:{$this->port} — {$errstr} ({$errno})");
        }

        stream_set_timeout($fp, $this->timeout);
        $this->connection = $fp;
        return $fp;
    }

    /**
     * Close persistent TCP connection.
     */
    protected function closeConnection(): void
    {
        if ($this->connection) {
            @fclose($this->connection);
            $this->connection = null;
        }
    }

    // ═══════════════════════════════════════════════════════
    //  Write operations
    // ═══════════════════════════════════════════════════════

    /**
     * Modify pocket and/or storehouse money via DBModifyRoleData RPC (opcode 8005).
     */
    public function modifyRoleMoney(int $roleId, ?int $pocketMoney = null, ?int $storeMoney = null): bool
    {
        $mask = 0;
        $pocket = 0;
        $store = 0;

        if ($pocketMoney !== null) {
            $mask |= self::MASK_POCKET_MONEY;
            $pocket = max(0, min($pocketMoney, 200000000));
        }
        if ($storeMoney !== null) {
            $mask |= self::MASK_STORE_MONEY;
            $store = max(0, min($storeMoney, 200000000));
        }

        if ($mask === 0) return true;

        $this->writeBuf = '';
        $this->writeInt32(-1);        // local session
        $this->writeInt32($roleId);   // roleid
        $this->writeInt32($mask);     // mask
        $this->writeInt32(0);         // level
        $this->writeInt64(0);         // exp (int64)
        $this->writeInt32($pocket);   // pocket_money
        $this->writeInt32($store);    // store_money
        $this->writeInt32(0);         // pkvalue
        $this->writeInt32(0);         // reputation
        $this->writeInt32(0);         // potential
        $this->writeInt32(0);         // occupation

        $payload = $this->writeBuf;
        $packet = $this->encodeCUint(self::OP_DB_MODIFY_ROLE)
                . $this->encodeCUint(strlen($payload))
                . $payload;

        $response = $this->tcpSend($packet);
        if ($response === null) return false;

        $this->readBuf = $response;
        $this->readPos = 0;
        $this->readCUint(); // opcode
        $this->readCUint(); // length
        $this->readInt32(); // local session
        $retCode = $this->readInt32();

        Cache::forget("pw.role.{$roleId}");

        return $retCode === 0;
    }

    /**
     * Apply GRoleStatus top-level field patches (same layout as updateRoleStatus, without Put).
     */
    public function applyStatusFieldPatches(string $rawStatus, array $changes): string
    {
        $patched = $rawStatus;

        if (isset($changes['cultivation'])) {
            $patched = substr_replace($patched, strrev(pack('i', (int) $changes['cultivation'])), 5, 4);
        }
        if (isset($changes['exp'])) {
            $patched = substr_replace($patched, strrev(pack('i', (int) $changes['exp'])), 9, 4);
        }
        if (isset($changes['sp'])) {
            $patched = substr_replace($patched, strrev(pack('i', (int) $changes['sp'])), 13, 4);
        }
        if (isset($changes['pos_x'])) {
            $patched = substr_replace($patched, strrev(pack('f', (float) $changes['pos_x'])), 29, 4);
        }
        if (isset($changes['pos_y'])) {
            $patched = substr_replace($patched, strrev(pack('f', (float) $changes['pos_y'])), 33, 4);
        }
        if (isset($changes['pos_z'])) {
            $patched = substr_replace($patched, strrev(pack('f', (float) $changes['pos_z'])), 37, 4);
        }
        if (isset($changes['world'])) {
            $patched = substr_replace($patched, strrev(pack('i', (int) $changes['world'])), 41, 4);
        }
        if (isset($changes['reputation'])) {
            $patched = substr_replace($patched, strrev(pack('i', (int) $changes['reputation'])), 57, 4);
        }

        return $patched;
    }

    /**
     * Set max AP (Vigor) inside extend_prop octets. pwAdmin allows 0, 99, 199, 299, 399 (maps to GRoleData.ep.max_ap).
     * max_ap is the last int32 in the fixed part of property blob (LE) at byte offset 144.
     */
    public function patchVigorInRawStatus(string $rawStatus, int $vigor): ?string
    {
        if (!in_array($vigor, [0, 99, 199, 299, 399], true)) {
            return null;
        }

        $this->readBuf = $rawStatus;
        $this->readPos = 0;
        if (strlen($rawStatus) < 80) {
            return null;
        }

        $this->readByte();
        for ($i = 0; $i < 5; $i++) {
            $this->readInt32();
        }
        $this->readInt32();
        $this->readInt32();
        for ($i = 0; $i < 3; $i++) {
            $this->readFloat();
        }
        for ($i = 0; $i < 5; $i++) {
            $this->readInt32();
        }
        for ($i = 0; $i < 4; $i++) {
            $this->readOctets();
        }
        for ($i = 0; $i < 7; $i++) {
            $this->readInt32();
        }
        $this->readOctets();
        $this->readInt16();
        $this->readOctets();

        $cuintPos = $this->readPos;
        $l = $this->readCUint();
        if ($l < 148) {
            return null;
        }
        $propStart = $this->readPos;
        if ($propStart + $l > strlen($this->readBuf)) {
            return null;
        }
        $propData = substr($this->readBuf, $propStart, $l);
        $le = pack('l', (int) $vigor);
        if (strlen($le) !== 4) {
            return null;
        }
        $patchedProp = substr_replace($propData, $le, 144, 4);
        if (strlen($patchedProp) !== strlen($propData)) {
            return null;
        }

        $cuintBlock = substr($this->readBuf, $cuintPos, $propStart - $cuintPos);
        $tail = substr($this->readBuf, $propStart + $l);

        return substr($this->readBuf, 0, $cuintPos) . $cuintBlock . $patchedProp . $tail;
    }

    /**
     * Update character status fields by patching raw status binary.
     *
     * Fixed-offset fields in GRoleStatus:
     *   cultivation(5), exp(9), sp(13), pos_x(29), pos_y(33),
     *   pos_z(37), world_tag(41), reputation(57)
     */
    public function updateRoleStatus(int $roleId, string $rawStatus, array $changes): bool
    {
        $patched = $this->applyStatusFieldPatches($rawStatus, $changes);

        return $this->putRoleStatus($roleId, $patched);
    }

    /**
     * Send modified GRoleStatus to gamedbd via PutRoleStatus RPC (opcode 3014).
     */
    /**
     * @internal Used by MemberController when combining field patches + vigor patch
     */
    public function putRoleStatusData(int $roleId, string $statusData): bool
    {
        return $this->putRoleStatus($roleId, $statusData);
    }

    protected function putRoleStatus(int $roleId, string $statusData): bool
    {
        $this->writeBuf = '';
        $this->writeInt32(-1);      // local session
        $this->writeInt32($roleId); // RoleId
        $this->writeBuf .= $statusData;

        $payload = $this->writeBuf;
        $packet = $this->encodeCUint(self::OP_PUT_ROLE_STATUS)
                . $this->encodeCUint(strlen($payload))
                . $payload;

        $response = $this->tcpSend($packet);
        if ($response === null) return false;

        $this->readBuf = $response;
        $this->readPos = 0;
        $this->readCUint(); // opcode
        $this->readCUint(); // length
        $this->readInt32(); // local session
        $retCode = $this->readInt32();

        Cache::forget("pw.role.{$roleId}");

        return $retCode === 0;
    }
}
