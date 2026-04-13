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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class News extends Model
{
    protected $table = 'pw_news';

    protected $fillable = [
        'title', 'slug', 'thumbnail', 'content',
        'category', 'tags', 'is_published', 'author_id', 'views',
    ];

    protected function casts(): array
    {
        return [
            'tags'         => 'array',
            'is_published' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (News $news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title) . '-' . Str::random(4);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_published', true);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'ID');
    }

    /**
     * Auto-generated excerpt from content if not stored separately.
     */
    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->content), 120);
    }
}
