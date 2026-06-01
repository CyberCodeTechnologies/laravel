<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'verified',
        'website',
        'instagram',
        'twitter',
        'profile_image',
    ];

    protected $casts = [
        'verified' => 'boolean',
    ];

    /**
     * Get the user that owns the artist profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the artworks for the artist.
     */
    public function artworks(): HasMany
    {
        return $this->hasMany(Artwork::class, 'artist_id', 'user_id');
    }

    /**
     * Get the certificates for the artist.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'artist_id', 'user_id');
    }

    /**
     * Get the sales for the artist.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Transaction::class, 'seller_id', 'user_id');
    }

    /**
     * Get the followers of the artist.
     */
    public function followers(): HasMany
    {
        return $this->hasMany(Follower::class, 'following_id', 'user_id');
    }

    /**
     * Get the profile image URL.
     */
    public function getProfileImageUrlAttribute(): string
    {
        if (!$this->profile_image) {
            return asset('images/placeholder-avatar.jpg');
        }
        
        if (str_starts_with($this->profile_image, 'http://') || str_starts_with($this->profile_image, 'https://')) {
            return $this->profile_image;
        }
        
        return asset('storage/' . $this->profile_image);
    }

    /**
     * Scope a query to only include verified artists.
     */
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    /**
     * Get the artist's full name from the user relationship.
     */
    public function getNameAttribute(): string
    {
        return $this->user?->name ?? '';
    }

    /**
     * Get the artist's email from the user relationship.
     */
    public function getEmailAttribute(): string
    {
        return $this->user?->email ?? '';
    }

    /**
     * Get the artist's slug from the user relationship.
     */
    public function getSlugAttribute(): string
    {
        return $this->user?->slug ?? '';
    }

    /**
     * Get the artist's avatar from the user relationship.
     */
    public function getAvatarAttribute(): string
    {
        return $this->user?->avatar ?? '';
    }

    /**
     * Get the artist's cover image from the user relationship.
     */
    public function getCoverImageAttribute(): string
    {
        return $this->user?->cover_image ?? '';
    }

    /**
     * Get the artist's specialization from the user relationship.
     */
    public function getSpecializationAttribute(): string
    {
        return $this->user?->specialization ?? '';
    }

    /**
     * Get the artist's artist statement from the user relationship.
     */
    public function getArtistStatementAttribute(): string
    {
        return $this->user?->artist_statement ?? '';
    }

    /**
     * Get the artist's education from the user relationship.
     */
    public function getEducationAttribute(): array
    {
        return $this->user?->education ?? [];
    }

    /**
     * Get the artist's exhibitions from the user relationship.
     */
    public function getExhibitionsAttribute(): array
    {
        return $this->user?->exhibitions ?? [];
    }

    /**
     * Get the artist's awards from the user relationship.
     */
    public function getAwardsAttribute(): array
    {
        return $this->user?->awards ?? [];
    }

    /**
     * Get the artist's press from the user relationship.
     */
    public function getPressAttribute(): array
    {
        return $this->user?->press ?? [];
    }

    /**
     * Get the artist's location from the user relationship.
     */
    public function getLocationAttribute(): string
    {
        return $this->user?->location ?? '';
    }

    /**
     * Get the artist's years active from the user relationship.
     */
    public function getYearsActiveAttribute(): int
    {
        return $this->user?->years_active ?? 0;
    }
}
