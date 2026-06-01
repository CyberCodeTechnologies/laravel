<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the artworks in this category.
     */
    public function artworks(): HasMany
    {
        return $this->hasMany(Artwork::class);
    }

    /**
     * Get the active artworks in this category.
     */
    public function activeArtworks(): HasMany
    {
        return $this->hasMany(Artwork::class)->whereIn('status', ['approved', 'sold']);
    }

    /**
     * Get the image URL.
     */
    public function getImageUrlAttribute(): string
    {
        return $this->image 
            ? asset('storage/' . $this->image) 
            : asset('images/placeholder-artwork.jpg');
    }
}
