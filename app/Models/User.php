<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Services\GameDbService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use App\Notifications\ResetPasswordNotification;

/**
 * Perfect World User Model
 *
 * Maps to the game's native `users` table.
 * Panel-specific columns are added via migration (alter table).
 *
 * Password format: bcrypt(username + password)
 * PIN (qq field): 4-6 digit numeric string
 * ID: starts at 1024, increments by +16 (PW game format)
 *
 * @property int    $ID
 * @property string $name
 * @property string $passwd
 * @property string $passwd2
 * @property string $qq           PIN game
 * @property string $email
 * @property string $truename
 * @property string $phonenumber
 * @property string $role         admin | webadmin | partner | gm | player
 * @property int    $money        Panel gold balance
 * @property int    $bonuses      Bonus points
 * @property string $language
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Game's users table — shared with game server.
     */
    protected $table = 'users';

    /**
     * PW uses non-standard primary key starting at 1024, +16 increments.
     */
    protected $primaryKey = 'ID';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'ID',
        'name',
        'email',
        'phonenumber',
        'passwd',
        'passwd2',
        'qq',
        'truename',
        'role',
        'money',
        'bonuses',
        'language',
        'creatime',
        'profile_photo_path',
        'referral_code',
        'referred_by',
        'register_ip',
    ];

    protected $hidden = [
        'passwd',
        'passwd2',
        'qq',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'       => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'last_active_at'          => 'datetime',
            'creatime'                => 'datetime',
            'money'                   => 'integer',
            'bonuses'                 => 'integer',
        ];
    }

    /**
     * Override: Laravel uses `password` field by default.
     * PW stores hash in `passwd` field.
     * Hash format: bcrypt(username + password)
     */
    public function getAuthPassword(): string
    {
        return $this->passwd;
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->referral_code)) {
                $user->referral_code = self::generateReferralCode();
            }
        });
    }

    public static function generateReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Generate next PW-format user ID.
     * PW format: starts at 1024, increments by +16.
     */
    public static function nextId(): int
    {
        $max = static::max('ID');
        return $max ? $max + 16 : 1024;
    }

    // -------------------------------------------------------
    // Role helpers
    // -------------------------------------------------------

    public function isAdministrator(): bool
    {
        return $this->role === 'admin';
    }

    public function isGamemaster(): bool
    {
        // GM ditentukan dari tabel auth game (bukan kolom role di panel)
        return Cache::remember('pw.user.isgm.' . $this->ID, 300, function () {
            try {
                return DB::connection('mysql_game')
                    ->table('auth')
                    ->where('userid', $this->ID)
                    ->exists();
            } catch (\Throwable $e) {
                return $this->role === 'gm';
            }
        });
    }

    public function isWebAdmin(): bool
    {
        return in_array($this->role, ['admin', 'webadmin']);
    }

    public function isPartner(): bool
    {
        return $this->role === 'partner';
    }

    public function isPlayer(): bool
    {
        return $this->role === 'player';
    }

    // -------------------------------------------------------
    // Balance helpers
    // -------------------------------------------------------

    public function getFormattedMoneyAttribute(): string
    {
        return number_format($this->money ?? 0, 0, ',', '.');
    }

    public function getFormattedBonusesAttribute(): string
    {
        return number_format($this->bonuses ?? 0, 0, ',', '.');
    }

    /**
     * Check if user is currently online (has active session on game server).
     */
    public function isOnline(): bool
    {
        return Cache::remember('pw.user.online.' . $this->ID, 30, function () {
            try {
                return DB::table('point')
                    ->where('uid', $this->ID)
                    ->where('zoneid', 1)
                    ->exists();
            } catch (\Throwable $e) {
                return false;
            }
        });
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'user_id', 'ID');
    }

    public function shopLogs(): HasMany
    {
        return $this->hasMany(ShopLog::class, 'user_id', 'ID');
    }

    public function voteLogs(): HasMany
    {
        return $this->hasMany(VoteLog::class, 'user_id', 'ID');
    }

    public function voucherLogs(): HasMany
    {
        return $this->hasMany(VoucherLog::class, 'user_id', 'ID');
    }

    public function serviceLogs(): HasMany
    {
        return $this->hasMany(ServiceLog::class, 'user_id', 'ID');
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by', 'ID');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by', 'ID');
    }

    public function referralRewards(): HasMany
    {
        return $this->hasMany(ReferralReward::class, 'referrer_id', 'ID');
    }

    public function referralPartner(): HasOne
    {
        return $this->hasOne(ReferralPartner::class, 'user_id', 'ID');
    }

    public function isReferralPartner(): bool
    {
        return $this->referralPartner()->where('is_active', true)->exists();
    }

    // -------------------------------------------------------
    // Game Characters
    // -------------------------------------------------------

    private const CLASS_MAP = [
        0 => 'Blademaster',
        1 => 'Wizard',
        2 => 'Psychic',
        3 => 'Venomancer',
        4 => 'Barbarian',
        5 => 'Assassin',
        6 => 'Archer',
        7 => 'Cleric',
        8 => 'Seeker',
        9 => 'Mystic',
        10 => 'Duskblade',
        11 => 'Stormbringer',
    ];

    /**
     * Get all game characters (roles) for this account.
     *
     * Sumber utama: MySQL `roles` WHERE account_id = userId (diisi Tomcat saat sync).
     * Fallback: GetUser(3002) dari gamedbd — daftar role_id milik akun ini.
     */
    public static function gameCharacterRoleIdSlotBoundsForUserId(int $userId): ?array
    {
        return null;
    }

    public function gameCharacterRoleIdSlotBounds(): ?array
    {
        return null;
    }

    public function gameCharacters(): \Illuminate\Support\Collection
    {
        return Cache::remember('pw.user.characters.v10.' . $this->ID, 30, function () {
            $uid = (int) $this->ID;
            $gameDb = new GameDbService();

            $out = $this->loadGameCharactersByAccountId($gameDb, $uid);
            if ($out->isNotEmpty()) {
                return $out;
            }

            return $this->loadGameCharactersFromGetUserInRange(
                $gameDb,
                $uid,
                PHP_INT_MIN,
                PHP_INT_MAX
            );
        });
    }

    /**
     * @return \Illuminate\Support\Collection<int, object>
     */
    private function loadGameCharactersByAccountId(
        GameDbService $gameDb,
        int $uid
    ): \Illuminate\Support\Collection {
        $out = collect();
        try {
            $fromMysql = DB::connection('mysql_game')
                ->table('roles')
                ->where('account_id', $uid)
                ->get();
        } catch (\Throwable $e) {
            return $out;
        }

        foreach ($fromMysql as $r) {
            $d = $gameDb->getRoleData((int) $r->role_id);
            if ($d === null) {
                $out->push($this->mapGameRoleTableRow($r));

                continue;
            }
            if ((int) ($d['base']['userid'] ?? 0) === $uid) {
                // Prefer realtime level/class/gender from gamedb binary blob
                // (MySQL roles.role_level is only updated periodically by gamed).
                $out->push($this->mapGameRoleTableRow($r, $d));
            }
        }

        return $out;
    }

    /**
     * @return \Illuminate\Support\Collection<int, object>
     */
    private function loadGameCharactersFromGetUserInRange(
        GameDbService $gameDb,
        int $uid,
        int $minRid,
        int $maxRid
    ): \Illuminate\Support\Collection {
        $userPack = $gameDb->getUserDataFromGamedb($uid);
        if ($userPack === null
            || ! is_array($userPack['role_ids'] ?? null)
            || count($userPack['role_ids']) === 0) {
            return collect();
        }

        $ids = array_map('intval', $userPack['role_ids']);
        if ($minRid !== PHP_INT_MIN || $maxRid !== PHP_INT_MAX) {
            $ids = array_values(array_filter(
                $ids,
                static fn (int $rid) => $rid >= $minRid && $rid <= $maxRid
            ));
        }
        sort($ids, SORT_NUMERIC);
        if (count($ids) === 0) {
            return collect();
        }

        $rows = DB::connection('mysql_game')
            ->table('roles')
            ->whereIn('role_id', $ids)
            ->get()
            ->keyBy('role_id');

        $out = collect();
        foreach ($ids as $rid) {
            $row = $rows->get($rid);
            if ($row) {
                $out->push($this->mapGameRoleTableRow($row));

                continue;
            }
            $live = $gameDb->getRoleData($rid);
            if (! $live) {
                continue;
            }
            $b = $live['base'];
            $s = $live['status'] ?? [];
            $out->push((object) [
                'role_id'    => $rid,
                'name'       => $b['name'] ?? '?',
                'level'      => (int) ($s['level'] ?? 0),
                'class'      => self::CLASS_MAP[$b['cls'] ?? 0] ?? 'Unknown',
                'class_id'   => (int) ($b['cls'] ?? 0),
                'gender'     => (int) ($b['gender'] ?? 0),
            ]);
        }

        return $out;
    }

    private function mapGameRoleTableRow(object $r, ?array $live = null): object
    {
        $base = $live['base'] ?? [];
        $status = $live['status'] ?? [];

        return (object) [
            'role_id'    => (int) $r->role_id,
            'name'       => $base['name'] ?? $r->role_name,
            // Prefer realtime level from gamedb binary; fallback to MySQL column.
            'level'      => (int) ($status['level'] ?? $r->role_level),
            'class'      => self::CLASS_MAP[$base['cls'] ?? $r->role_occupation] ?? 'Unknown',
            'class_id'   => (int) ($base['cls'] ?? $r->role_occupation),
            'gender'     => (int) ($base['gender'] ?? $r->role_gender),
        ];
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}

