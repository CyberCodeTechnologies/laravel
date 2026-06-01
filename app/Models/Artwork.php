<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Scopes\ApprovedScope;
use Illuminate\Support\Str;

#[ScopedBy(ApprovedScope::class)]
class Artwork extends Model
{
    // Laravel Scout `Searchable` trait is optional. If you want full-text
    // search powered by Scout, install `laravel/scout` and re-add the trait:
    // `use HasFactory, \Laravel\Scout\Searchable;`
    use HasFactory;

    protected $fillable = [
        'artist_id',
        'category_id',
        'title',
        'slug',
        'description',
        'medium',
        'dimensions',
        'price',
        'currency',
        'price_usd',
        'price_mmk',
        'year',
        'images',
        'status',
        'is_featured',
        'views_count',
        'likes_count',
        'approved_at',
        'stock',
        'weight',
        'is_digital',
        // Additional workflow fields
        'is_archived',
        'archived_at',
        'is_reserved',
        'reserved_by',
        'reserved_until',
        'under_review',
        'review_notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_usd' => 'decimal:2',
        'price_mmk' => 'decimal:0',
        'images' => 'array',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'approved_at' => 'datetime',
        'stock' => 'integer',
        'is_digital' => 'boolean',
        'weight' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($artwork) {
            if ($artwork->isDirty('price') || $artwork->isDirty('currency')) {
                $artwork->syncConvertedPrices();
            }
        });
    }

    /**
     * Sync converted prices based on current price and currency.
     */
    public function syncConvertedPrices(): void
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        
        $this->price_usd = $this->currency === 'USD' 
            ? $this->price 
            : $currencyService->convert($this->price, $this->currency, 'USD');
            
        $this->price_mmk = $this->currency === 'MMK' 
            ? $this->price 
            : $currencyService->convert($this->price, $this->currency, 'MMK');
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'artist' => $this->artist ? $this->artist->name : null,
            'category' => $this->category ? $this->category->name : null,
            'medium' => $this->medium,
            'price_usd' => (float) $this->price_usd,
            'price_mmk' => (float) $this->price_mmk,
            'status' => $this->status,
            'year' => (int) $this->year,
            'created_at' => $this->created_at,
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the artist that created the artwork.
     */
    public function artist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    /**
     * Get the category of the artwork.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the certificate for the artwork.
     */
    public function certificate(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Get the ownerships of the artwork.
     */
    public function ownerships(): HasMany
    {
        return $this->hasMany(Ownership::class);
    }

    /**
     * Get the current owner of the artwork.
     */
    public function currentOwner(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Ownership::class,
            'artwork_id',
            'id',
            'id',
            'owner_id'
        )->where('ownerships.is_current_owner', true);
    }

    /**
     * Get the transactions for the artwork.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the resales for the artwork.
     */
    public function resales(): HasMany
    {
        return $this->hasMany(Resale::class);
    }

    /**
     * Get the likes for the artwork.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Scope for approved artworks.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Check if the artwork is sold.
     */
    public function isSold(): bool
    {
        return $this->status === 'sold';
    }

    /**
     * Check if the artwork is available for resale.
     */
    public function isAvailableForResale(): bool
    {
        return $this->isSold() && !$this->activeResale()->exists();
    }

    /**
     * Get active resale listing.
     */
    public function activeResale()
    {
        return $this->resales()->where('status', 'listed');
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        return $currencyService->format($this->price, $this->currency);
    }

    /**
     * Update converted prices when base price changes.
     */
    public function updateConvertedPrices(): void
    {
        $this->syncConvertedPrices();
        $this->saveQuietly();
    }

    /**
     * Determine whether the artwork is available for direct purchase.
     */
    public function isAvailable(): bool
    {
        if ($this->isSold()) {
            return false;
        }

        if ($this->status !== 'approved') {
            return false;
        }

        if ($this->is_digital) {
            return true;
        }

        return $this->getAvailableStock() > 0;
    }

    /**
     * Check whether artwork is in stock (non-digital with positive stock).
     */
    public function isInStock(): bool
    {
        if ($this->is_digital) {
            return false;
        }

        return $this->getAvailableStock() > 0;
    }

    /**
     * Get the available stock count for the artwork.
     */
    public function getAvailableStock(): int
    {
        return max(0, (int) ($this->stock ?? 0));
    }

    /**
     * Determine if the artwork has at least the given quantity in stock.
     */
    public function hasStock(int $quantity = 1): bool
    {
        if ($this->is_digital) {
            return true;
        }

        return $this->getAvailableStock() >= $quantity;
    }

    /**
     * Get the primary image URL for the artwork.
     */
    public function getPrimaryImageAttribute(): string
    {
        $images = $this->images ?? [];
        if (!empty($images) && isset($images[0])) {
            $first = $images[0];
            if (Str::startsWith($first, ['http://', 'https://'])) {
                return $first;
            }

            if (Str::startsWith($first, '/')) {
                return asset(ltrim($first, '/'));
            }

            return asset('storage/' . ltrim($first, '/'));
        }

        return asset('images/placeholder-artwork.jpg');
    }

    /**
     * Get all image URLs for the artwork.
     */
    public function getImageUrlsAttribute(): array
    {
        $images = $this->images ?? [];

        $urls = collect($images)->map(function ($img) {
            if (Str::startsWith($img, ['http://', 'https://'])) {
                return $img;
            }

            if (Str::startsWith($img, '/')) {
                return asset(ltrim($img, '/'));
            }

            return asset('storage/' . ltrim($img, '/'));
        })->filter()->values()->toArray();

        if (empty($urls)) {
            return [asset('images/placeholder-artwork.jpg')];
        }

        return $urls;
    }

    /**
     * Increment the view count.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
