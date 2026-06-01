<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Exhibition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_date',
        'end_date',
        'venue',
        'address',
        'city',
        'country',
        'images',
        'featured_image',
        'status',
        'is_featured',
        'is_published',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'images' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($exhibition) {
            if (empty($exhibition->slug)) {
                $exhibition->slug = Str::slug($exhibition->title);
            }
        });
    }

    /**
     * Get the artworks for the exhibition.
     */
    public function artworks(): BelongsToMany
    {
        return $this->belongsToMany(Artwork::class, 'artwork_exhibition')
            ->withPivot('order')
            ->withTimestamps();
    }

    /**
     * Get the artists for the exhibition.
     */
    public function artists(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'artist_exhibition')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include published exhibitions.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to only include featured exhibitions.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include upcoming exhibitions.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', 'upcoming');
    }

    /**
     * Scope a query to only include ongoing exhibitions.
     */
    public function scopeOngoing(Builder $query): Builder
    {
        return $query->where('status', 'ongoing');
    }

    /**
     * Scope a query to only include completed exhibitions.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get the featured image URL.
     */
    public function getFeaturedImageUrlAttribute(): string
    {
        if (empty($this->featured_image)) {
            return asset('images/placeholder-exhibition.jpg');
        }
        
        if (str_starts_with($this->featured_image, 'http://') || str_starts_with($this->featured_image, 'https://')) {
            return $this->featured_image;
        }
        
        return asset('storage/' . $this->featured_image);
    }

    /**
     * Get the first image URL from gallery images.
     */
    public function getFirstImageUrlAttribute(): string
    {
        $images = $this->images ?? [];
        if (empty($images)) {
            return $this->featured_image_url;
        }
        
        $image = $images[0];
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }
        
        return asset('storage/' . $image);
    }

    /**
     * Get the status color for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'upcoming' => 'blue',
            'ongoing' => 'green',
            'completed' => 'gray',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get the date range string.
     */
    public function getDateRangeAttribute(): string
    {
        if (!$this->start_date) return '';
        
        $start = $this->start_date->format('M d, Y');
        if (!$this->end_date) return $start;
        
        $end = $this->end_date->format('M d, Y');
        return "{$start} - {$end}";
    }

    /**
     * Get the full location string.
     */
    public function getFullLocationAttribute(): string
    {
        $parts = array_filter([$this->venue, $this->address, $this->city, $this->country]);
        return implode(', ', $parts);
    }

    /**
     * Get all image URLs for the gallery.
     */
    public function getImageUrlsAttribute(): array
    {
        return collect($this->images ?? [])->map(function ($image) {
            if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
                return $image;
            }
            return asset('storage/' . $image);
        })->toArray();
    }

    /**
     * Get the artworks count.
     */
    public function getArtworksCountAttribute(): int
    {
        return $this->artworks()->count();
    }
}
