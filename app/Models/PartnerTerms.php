<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerTerms extends Model
{
    protected $table = 'pw_partner_terms';

    protected $fillable = ['lang', 'content'];

    /**
     * Get terms for a given language, falling back to 'id' if not found.
     */
    public static function forLang(string $lang): ?self
    {
        return static::where('lang', $lang)->first()
            ?? static::where('lang', 'id')->first();
    }
}
