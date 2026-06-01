<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'total_amount',
        'currency',
        'promo_code_id',
        'promo_code',
        'discount_amount',
        'subtotal_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'subtotal_amount' => 'decimal:2',
    ];

    /**
     * Get the items in the cart.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the user that owns the cart.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the promo code applied to this cart.
     */
    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }

    /**
     * Get the total formatted amount.
     */
    public function getFormattedTotalAttribute(): string
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        return $currencyService->format($this->total_amount, $this->currency);
    }

    /**
     * Get the count of items in the cart.
     */
    public function getItemCountAttribute(): int
    {
        return $this->items()->sum('quantity');
    }

    /**
     * Update cart total amount with promo code discount.
     */
    public function updateTotal(): void
    {
        $subtotal = $this->items()->sum('subtotal');
        $currency = $this->items()->first()?->currency ?? 'USD';
        
        // Calculate discount if promo code is applied
        $discount = 0;
        if ($this->promo_code_id && $this->promoCode) {
            $discount = $this->promoCode->calculateDiscount($subtotal);
        }
        
        $total = max(0, $subtotal - $discount);
        
        $this->update([
            'subtotal_amount' => $subtotal,
            'discount_amount' => $discount,
            'total_amount' => $total,
            'currency' => $currency,
        ]);
    }

    /**
     * Apply promo code to cart.
     */
    public function applyPromoCode(PromoCode $promoCode): bool
    {
        if (!$promoCode->isValid()) {
            return false;
        }
        
        $subtotal = $this->items()->sum('subtotal');
        
        if (!$promoCode->canApplyTo($subtotal)) {
            return false;
        }
        
        $this->update([
            'promo_code_id' => $promoCode->id,
            'promo_code' => $promoCode->code,
        ]);
        
        $this->updateTotal();
        return true;
    }

    /**
     * Remove promo code from cart.
     */
    public function removePromoCode(): void
    {
        $this->update([
            'promo_code_id' => null,
            'promo_code' => null,
            'discount_amount' => 0,
        ]);
        $this->updateTotal();
    }

    /**
     * Get formatted subtotal amount.
     */
    public function getFormattedSubtotalAttribute(): string
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        return $currencyService->format($this->subtotal_amount, $this->currency);
    }

    /**
     * Get formatted discount amount.
     */
    public function getFormattedDiscountAttribute(): string
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        return $currencyService->format($this->discount_amount, $this->currency);
    }

    /**
     * Create or get cart for session.
     */
    public static function getOrCreateCart(?string $sessionId = null): self
    {
        $sessionId = $sessionId ?? session()->getId();
        $userId = auth()->id();

        // For authenticated users, try to find cart by user_id first
        if ($userId) {
            $cart = self::where('user_id', $userId)->first();
            if ($cart) {
                // Update session_id to current session
                if ($cart->session_id !== $sessionId) {
                    $cart->update(['session_id' => $sessionId]);
                }
                return $cart;
            }
        }

        // For guest users or if no user cart exists, find/create by session_id
        $cart = self::where('session_id', $sessionId)->first();

        if (!$cart) {
            $cart = self::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'total_amount' => 0,
                'currency' => session('currency', 'USD'),
            ]);
        } elseif ($userId && !$cart->user_id) {
            // If cart exists as guest cart and user is now authenticated, associate it
            $cart->update(['user_id' => $userId]);
        }

        return $cart;
    }
}
