<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table      = 'pw_settings';
    protected $primaryKey = 'key';
    public    $incrementing = false;
    protected $keyType    = 'string';

    protected $fillable = ['key', 'value', 'group'];

    /**
     * Get a setting value by key, with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $raw = Cache::remember("setting_{$key}", 3600, function () use ($key) {
            return static::where('key', $key)->value('value');
        });

        // Treat empty string same as null
        return ($raw !== null && $raw !== '') ? $raw : $default;
    }

    /**
     * Set (upsert) a setting value and flush its cache.
     */
    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        Cache::forget("setting_{$key}");
    }

    /**
     * Flush all settings from cache.
     */
    public static function flushCache(): void
    {
        // Retrieve all keys and flush individually
        static::all()->each(fn ($s) => Cache::forget("setting_{$s->key}"));
    }
}
