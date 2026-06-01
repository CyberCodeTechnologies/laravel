<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resale extends Model
{
    use HasFactory;

    protected $fillable = [
        'artwork_id',
        'owner_id',
        'price',
        'currency',
        'price_usd',
        'price_mmk',
        'exchange_rate',
        'minimum_price',
        'description',
        'images',
        'status',
        'listed_at',
        'sold_at',
        'is_verified',
        'admin_notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_usd' => 'decimal:2',
        'price_mmk' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
        'minimum_price' => 'decimal:2',
        'images' => 'array',
        'listed_at' => 'datetime',
        'sold_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the artwork being resold.
     */
    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artwork::class);
    }

    /**
     * Get the owner who listed the resale.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Backward-compatible asking_price accessor (maps to price field).
     */
    public function getAskingPriceAttribute(): float
    {
        return $this->price;
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        $currency = session('currency', 'USD');
        $price = $currency === 'MMK' ? $this->price_mmk : $this->price_usd;
        return $currency === 'MMK' ? number_format($price) . ' MMK' : '$' . number_format($price);
    }

    /**
     * Get the price in a specific currency.
     */
    public function getPriceInCurrency(string $currency): float
    {
        return $currency === 'MMK' ? $this->price_mmk : $this->price_usd;
    }

    /**
     * Get the formatted price in a specific currency.
     */
    public function getFormattedPriceInCurrency(string $currency): string
    {
        $price = $this->getPriceInCurrency($currency);
        return $currency === 'MMK' ? number_format($price) . ' MMK' : '$' . number_format($price);
    }

    /**
     * Get the primary image URL.
     */
    public function getPrimaryImageAttribute(): string
    {
        $images = $this->images ?? [];
        if (!empty($images)) {
            return asset('storage/' . $images[0]);
        }
        
        // Fallback to artwork's primary image
        return $this->artwork?->primary_image ?? asset('images/placeholder-artwork.jpg');
    }

    /**
     * Get all image URLs.
     */
    public function getImageUrlsAttribute(): array
    {
        $resaleImages = collect($this->images ?? [])->map(function ($image) {
            return asset('storage/' . $image);
        })->toArray();

        // If no resale images, use artwork images
        if (empty($resaleImages) && $this->artwork) {
            return $this->artwork->image_urls;
        }

        return $resaleImages;
    }

    /**
     * Check if the resale is listed.
     */
    public function isListed(): bool
    {
        return $this->status === 'listed';
    }

    /**
     * Check if the resale is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved' || $this->status === 'listed';
    }

    /**
     * Check if the resale is sold.
     */
    public function isSold(): bool
    {
        return $this->status === 'sold';
    }

    /**
     * Check if the resale is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Approve the resale listing.
     */
    public function approve(): bool
    {
        return $this->update([
            'status' => 'listed',
            'listed_at' => now(),
            'is_verified' => true,
        ]);
    }

    /**
     * Reject the resale listing.
     */
    public function reject(string $reason = ''): bool
    {
        return $this->update([
            'status' => 'withdrawn',
            'admin_notes' => $reason,
        ]);
    }

    /**
     * Mark the resale as sold.
     */
    public function markAsSold(): bool
    {
        return $this->update([
            'status' => 'sold',
            'sold_at' => now(),
        ]);
    }

    /**
     * Withdraw the resale listing.
     */
    public function withdraw(): bool
    {
        return $this->update([
            'status' => 'withdrawn',
        ]);
    }

    /**
     * Verify ownership before listing.
     */
    public function verifyOwnership(): bool
    {
        if (!$this->artwork || !$this->owner) {
            return false;
        }

        $currentOwnership = $this->artwork->ownerships()
            ->where('owner_id', $this->owner->id)
            ->where('is_current_owner', true)
            ->first();

        return $currentOwnership !== null;
    }

    /**
     * Scope a query to only include listed resales.
     */
    public function scopeListed($query)
    {
        return $query->where('status', 'listed');
    }

    /**
     * Scope a query to only include pending resales.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include verified resales.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope a query to filter by owner.
     */
    public function scopeByOwner($query, $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }

    /**
     * Scope a query to filter by artwork.
     */
    public function scopeByArtwork($query, $artworkId)
    {
        return $query->where('artwork_id', $artworkId);
    }

    /**
     * Scope a query to filter by price range.
     */
    public function scopeByPriceRange($query, float $min, float $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Scope a query to order by newest listings first.
     */
    public function scopeNewest($query)
    {
        return $query->orderBy('listed_at', 'desc');
    }

    /**
     * Scope a query to order by price (low to high).
     */
    public function scopeByPriceAsc($query)
    {
        return $query->orderBy('price', 'asc');
    }

    /**
     * Scope a query to order by price (high to low).
     */
    public function scopeByPriceDesc($query)
    {
        return $query->orderBy('price', 'desc');
    }
}
