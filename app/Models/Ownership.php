<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ownership extends Model
{
    use HasFactory;

    protected $fillable = [
        'artwork_id',
        'owner_id',
        'acquired_at',
        'is_current_owner',
        'purchase_price',
        'transaction_type',
        'notes',
    ];

    protected $casts = [
        'acquired_at' => 'datetime',
        'is_current_owner' => 'boolean',
        'purchase_price' => 'decimal:2',
    ];

    /**
     * Get the artwork of this ownership.
     */
    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artwork::class);
    }

    /**
     * Get the owner of the artwork.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the formatted purchase price.
     */
    public function getFormattedPurchasePriceAttribute(): string
    {
        return $this->purchase_price ? '$' . number_format($this->purchase_price, 2) : 'N/A';
    }

    /**
     * Transfer ownership to a new owner.
     */
    public function transferTo(User $newOwner, float $purchasePrice = null, string $transactionType = 'sale'): Ownership
    {
        // Mark current ownership as not current
        $this->update(['is_current_owner' => false]);

        // Create new ownership record
        return static::create([
            'artwork_id' => $this->artwork_id,
            'owner_id' => $newOwner->id,
            'acquired_at' => now(),
            'is_current_owner' => true,
            'purchase_price' => $purchasePrice,
            'transaction_type' => $transactionType,
        ]);
    }

    /**
     * Scope a query to only include current owners.
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current_owner', true);
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
}
