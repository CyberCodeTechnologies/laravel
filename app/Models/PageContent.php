<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PageContent extends Model
{
    protected $fillable = [
        'page',
        'section',
        'key',
        'content_en',
        'content_my',
        'type',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get content for a specific page
     */
    public function scopeForPage(Builder $query, string $page): Builder
    {
        return $query->where('page', $page);
    }

    /**
     * Scope to get content for a specific section
     */
    public function scopeForSection(Builder $query, string $section): Builder
    {
        return $query->where('section', $section);
    }

    /**
     * Scope to get active content only
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Get content by key for current locale
     */
    public function getContentAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'my' && $this->content_my ? $this->content_my : $this->content_en;
    }

    /**
     * Get content by key helper
     */
    public static function getByKey(string $key, $default = null): ?string
    {
        $content = self::where('key', $key)->active()->first();
        return $content ? $content->content : $default;
    }
}
