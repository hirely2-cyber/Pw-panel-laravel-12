<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class DeliveryProtocol
{
    // Protocol type IDs
    const TYPE_ANNOUNCE_LINK    = 372;
    const TYPE_SET_MAX_ONLINE   = 373;
    const TYPE_SET_MAX_ONLINE_RE = 374;
    const TYPE_GET_MAX_ONLINE   = 375;
    const TYPE_GM_GET_ATTRI     = 376;
    const TYPE_GM_SET_ATTRI     = 377;

    // Game attribute bytes (Java signed -> PHP unsigned)
    const ATTR_DOUBLE_EXP  = 0xCC; // 204
    const ATTR_LAMBDA      = 0xCD; // 205
    const ATTR_NO_TRADE    = 0xCF; // 207
    const ATTR_NO_AUCTION  = 0xD0; // 208
    const ATTR_NO_MAIL     = 0xD1; // 209
    const ATTR_NO_FACTION  = 0xD2; // 210
    const ATTR_DOUBLE_COIN = 0xD3; // 211
    const ATTR_DOUBLE_DROP = 0xD4; // 212
    const ATTR_DOUBLE_SP   = 0xD5; // 213
    const ATTR_NO_SHOP     = 0xD6; // 214

    private string $host;
    private int $port;
    private int $timeout;
    private static int $xidCounter = 0;

    /** @var resource|null Persistent socket for multi-call sessions */
    private $sock = null;
    private string $readBuffer = '';
    private bool $handshakeDone = false;

    public function __construct(?string $host = null, ?int $port = null, int $timeout = 5)
    {
        $this->host    = $host ?? config('pw-config.server.ip', '127.0.0.1');
        $this->port    = $port ?? (int) config('pw-api.ports.gdeliveryd', 29100);
        $this->timeout = $timeout;
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    // -- compact_uint32 encode (big-endian) --

    public static function packCompactUint32(int $n): string
    {
        if ($n < 0) {
            throw new \InvalidArgumentException('compact_uint32 does not support negative values');
        }
        if ($n < 64) {
            return chr($n);
        }
        if ($n < 16384) {
            return pack('n', $n | 0x8000);
        }
        if ($n < 536870912) {
            return pack('N', $n | 0xC0000000);
        }
        return chr(0xE0) . pack('N', $n);
    }

    // -- compact_uint32 decode --

    public static function unpackCompactUint32(string $data, int &$offset): int
    {
        if ($offset >= strlen($data)) {
            throw new \RuntimeException('Not enough data for compact_uint32');
        }

        $b = ord($data[$offset]);
        $top3 = $b & 0xE0;

        if ($top3 < 0x80) {
            $offset++;
            return $b;
        }
        if ($top3 === 0x80 || $top3 === 0xA0) {
            if ($offset + 1 >= strlen($data)) {
                throw new \RuntimeException('Not enough data for 2-byte compact_uint32');
            }
            $val = (($b & 0x7F) << 8) | ord($data[$offset + 1]);
            $offset += 2;
            return $val;
        }
        if ($top3 === 0xC0) {
            if ($offset + 3 >= strlen($data)) {
                throw new \RuntimeException('Not enough data for 4-byte compact_uint32');
            }
            $val = unpack('N', substr($data, $offset, 4))[1] & 0x3FFFFFFF;
            $offset += 4;
            return $val;
        }
        // 5 bytes: 0xE0 prefix
        if ($offset + 4 >= strlen($data)) {
            throw new \RuntimeException('Not enough data for 5-byte compact_uint32');
        }
        $offset++;
        $val = unpack('N', substr($data, $offset, 4))[1];
        $offset += 4;
        return $val;
    }

    // -- fixed-size marshal helpers (big-endian) --

    public static function marshalInt(int $v): string
    {
        return pack('N', $v);
    }

    public static function marshalByte(int $v): string
    {
        return chr($v & 0xFF);
    }

    public static function marshalOctets(string $raw): string
    {
        return self::packCompactUint32(strlen($raw)) . $raw;
    }

    public static function unmarshalInt(string $data, int &$offset): int
    {
        if ($offset + 4 > strlen($data)) {
            throw new \RuntimeException('Not enough data for unmarshalInt (need 4, have ' . (strlen($data) - $offset) . ')');
        }
        $val = unpack('N', substr($data, $offset, 4))[1];
        $offset += 4;
        if ($val >= 0x80000000) {
            $val -= 0x100000000;
        }
        return $val;
    }

    public static function unmarshalByte(string $data, int &$offset): int
    {
        if ($offset >= strlen($data)) {
            throw new \RuntimeException('Not enough data for unmarshalByte');
        }
        $v = ord($data[$offset]);
        $offset++;
        return $v;
    }

    public static function unmarshalOctets(string $data, int &$offset): string
    {
        $len = self::unpackCompactUint32($data, $offset);
        if ($offset + $len > strlen($data)) {
            throw new \RuntimeException('Not enough data for unmarshalOctets');
        }
        $raw = substr($data, $offset, $len);
        $offset += $len;
        return $raw;
    }

    // -- Packet building --

    private static function buildPacket(int $type, string $body): string
    {
        return self::packCompactUint32($type) . self::marshalOctets($body);
    }

    private static function nextXid(): int
    {
        return ++self::$xidCounter;
    }

    // -- Connection management --

    private function ensureConnected(): void
    {
        if ($this->sock && !feof($this->sock)) {
            return;
        }

        $this->sock = @fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
        if (!$this->sock) {
            throw new \RuntimeException("Cannot connect to gdeliveryd at {$this->host}:{$this->port} - {$errstr}");
        }
        stream_set_timeout($this->sock, $this->timeout);
        $this->readBuffer = '';
        $this->handshakeDone = false;

        // Send AnnounceLinkType (type=372, body: link_type=0)
        fwrite($this->sock, self::buildPacket(self::TYPE_ANNOUNCE_LINK, self::marshalByte(0)));
        $this->handshakeDone = true;
    }

    public function disconnect(): void
    {
        if ($this->sock) {
            @fclose($this->sock);
            $this->sock = null;
            $this->readBuffer = '';
            $this->handshakeDone = false;
        }
    }

    // -- Buffered packet reading --

    /**
     * Try to extract one complete packet from the read buffer.
     * Returns [type, bodyData] or null if no complete packet available.
     */
    private function tryParsePacket(): ?array
    {
        if (strlen($this->readBuffer) < 2) {
            return null;
        }

        $off = 0;
        try {
            $type = self::unpackCompactUint32($this->readBuffer, $off);
            if ($off >= strlen($this->readBuffer)) {
                return null;
            }
            $bodyLen = self::unpackCompactUint32($this->readBuffer, $off);
            if (strlen($this->readBuffer) < $off + $bodyLen) {
                return null; // incomplete body
            }
            $bodyData = substr($this->readBuffer, $off, $bodyLen);
            $this->readBuffer = substr($this->readBuffer, $off + $bodyLen);
            return [$type, $bodyData];
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Read packets from socket until we find one with the specified type.
     * Discards any other packet types (e.g. handshake responses).
     */
    private function readPacketByType(int $expectedType): string
    {
        $deadline = microtime(true) + $this->timeout;

        while (microtime(true) < $deadline) {
            // Try to parse from existing buffer first
            while ($parsed = $this->tryParsePacket()) {
                [$type, $bodyData] = $parsed;
                if ($type === $expectedType) {
                    return $bodyData;
                }
                // Not our type, skip (handshake, init messages, etc.)
            }

            // Need more data from socket
            $remaining = max(0.1, $deadline - microtime(true));
            stream_set_timeout($this->sock, (int) $remaining, (int)(($remaining - (int)$remaining) * 1000000));
            $chunk = @fread($this->sock, 4096);

            if ($chunk === false || $chunk === '') {
                if (feof($this->sock)) {
                    break;
                }
                usleep(10000);
                continue;
            }
            $this->readBuffer .= $chunk;
        }

        throw new \RuntimeException("Timeout waiting for packet type {$expectedType}");
    }

    // -- RPC --

    private function sendRpc(int $type, string $argBody): string
    {
        $this->ensureConnected();

        $xid = self::nextXid();
        $body = self::marshalInt($xid | 0x80000000) . $argBody;
        fwrite($this->sock, self::buildPacket($type, $body));

        // Read response — skip any non-matching packets (handshake, etc.)
        $bodyData = $this->readPacketByType($type);

        // Parse: xid(4 bytes) + result fields
        $boff = 0;
        $respXid = self::unmarshalInt($bodyData, $boff);

        return substr($bodyData, $boff);
    }

    // -- High-level API --

    /**
     * Get max online users info.
     * @return array [retcode, maxnum, fake_maxnum, curnum]
     */
    public function getMaxOnline(): array
    {
        $argBody = self::marshalInt(0); // padding=0
        $result = $this->sendRpc(self::TYPE_GET_MAX_ONLINE, $argBody);

        $off = 0;
        return [
            'retcode'      => self::unmarshalInt($result, $off),
            'maxnum'       => self::unmarshalInt($result, $off),
            'fake_maxnum'  => self::unmarshalInt($result, $off),
            'curnum'       => self::unmarshalInt($result, $off),
        ];
    }

    /**
     * Set max online users.
     */
    public function setMaxOnline(int $maxnum, int $fakeMaxnum): bool
    {
        $this->ensureConnected();

        $body = self::marshalInt($maxnum) . self::marshalInt($fakeMaxnum);
        fwrite($this->sock, self::buildPacket(self::TYPE_SET_MAX_ONLINE, $body));

        // Response comes as type 374 (SetMaxOnlineNum_Re)
        try {
            $bodyData = $this->readPacketByType(self::TYPE_SET_MAX_ONLINE_RE);
            $boff = 0;
            $retcode = self::unmarshalInt($bodyData, $boff);
            return $retcode === 0;
        } catch (\Throwable $e) {
            Log::warning("DeliveryProtocol: setMaxOnline failed: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Get a game attribute value (single byte).
     */
    public function getGameAttribute(int $attribute): ?int
    {
        $argBody = self::marshalInt(-1)          // gmroleid = -1
                 . self::marshalInt(-1)          // localsid = -1
                 . self::marshalByte($attribute);
        $result = $this->sendRpc(self::TYPE_GM_GET_ATTRI, $argBody);

        if (strlen($result) === 0) return null;

        $off = 0;
        $valueOctets = self::unmarshalOctets($result, $off);

        if (strlen($valueOctets) === 0) return null;

        return ord($valueOctets[0]) & 0xFF;
    }

    /**
     * Set a game attribute value (single byte).
     */
    public function setGameAttribute(int $attribute, int $value): bool
    {
        $valueOctets = self::marshalByte($value);
        $argBody = self::marshalInt(-1)                // gmroleid = -1
                 . self::marshalInt(-1)                // localsid = -1
                 . self::marshalByte($attribute)
                 . self::marshalOctets($valueOctets);  // value as Octets
        $result = $this->sendRpc(self::TYPE_GM_SET_ATTRI, $argBody);

        $off = 0;
        $retcode = self::unmarshalInt($result, $off);
        return $retcode === 0;
    }

    // -- Convenience methods --

    /**
     * Get all game attributes using a single connection.
     */
    public function getAllAttributes(): array
    {
        $attrs = [
            'double_exp'   => ['attr' => self::ATTR_DOUBLE_EXP,  'label' => 'Double EXP',        'type' => 'byte'],
            'lambda'       => ['attr' => self::ATTR_LAMBDA,      'label' => 'Lambda Value',      'type' => 'byte'],
            'double_drop'  => ['attr' => self::ATTR_DOUBLE_DROP, 'label' => 'Double Drop Rate',  'type' => 'bool'],
            'double_coin'  => ['attr' => self::ATTR_DOUBLE_COIN, 'label' => 'Double Coins',      'type' => 'bool'],
            'double_sp'    => ['attr' => self::ATTR_DOUBLE_SP,   'label' => 'Double SP',         'type' => 'bool'],
            'no_mail'      => ['attr' => self::ATTR_NO_MAIL,     'label' => 'No-Mail Mode',      'type' => 'bool'],
            'no_faction'   => ['attr' => self::ATTR_NO_FACTION,  'label' => 'No-Faction Mode',   'type' => 'bool'],
            'no_trade'     => ['attr' => self::ATTR_NO_TRADE,    'label' => 'No-Trade Mode',     'type' => 'bool'],
            'no_shop'      => ['attr' => self::ATTR_NO_SHOP,     'label' => 'No-PlayerShop Mode','type' => 'bool'],
            'no_auction'   => ['attr' => self::ATTR_NO_AUCTION,  'label' => 'No-Auction Mode',   'type' => 'bool'],
        ];

        $result = [];
        foreach ($attrs as $key => $info) {
            try {
                $val = $this->getGameAttribute($info['attr']);
                $result[$key] = [
                    'value' => $val,
                    'label' => $info['label'],
                    'type'  => $info['type'],
                ];
            } catch (\Throwable $e) {
                Log::warning("DeliveryProtocol: failed to get {$key}: {$e->getMessage()}");
                $result[$key] = [
                    'value' => null,
                    'label' => $info['label'],
                    'type'  => $info['type'],
                    'error' => true,
                ];
            }
        }

        return $result;
    }
}
