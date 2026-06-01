<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'artwork_id',
        'buyer_id',
        'seller_id',
        'transaction_id',
        'amount',
        'currency',
        'price_usd',
        'price_mmk',
        'exchange_rate',
        'platform_fee',
        'seller_earnings',
        'type',
        'status',
        'payment_method',
        'payment_id',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'price_usd' => 'decimal:2',
        'price_mmk' => 'decimal:0',
        'exchange_rate' => 'decimal:6',
        'platform_fee' => 'decimal:2',
        'seller_earnings' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the artwork of this transaction.
     */
    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artwork::class);
    }

    /**
     * Get the buyer of this transaction.
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Get the seller of this transaction.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedAmountAttribute(): string
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        return $currencyService->format($this->amount, $this->currency ?? 'USD');
    }

    /**
     * Get the price in a specific currency.
     */
    public function getAmountInCurrency(string $currency): float
    {
        if ($this->currency === $currency) {
            return $this->amount;
        }

        // Use cached converted prices if available
        if ($currency === 'USD' && $this->price_usd !== null) {
            return $this->price_usd;
        }
        if ($currency === 'MMK' && $this->price_mmk !== null) {
            return $this->price_mmk;
        }

        // Convert using currency service
        $currencyService = app(\App\Services\CurrencyService::class);
        return $currencyService->convert($this->amount, $this->currency ?? 'USD', $currency);
    }

    /**
     * Get the formatted price in a specific currency.
     */
    public function getFormattedAmountInCurrency(string $currency): string
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        $amount = $this->getAmountInCurrency($currency);
        return $currencyService->format($amount, $currency);
    }

    /**
     * Get the formatted commission.
     */
    public function getFormattedPlatformFeeAttribute(): string
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        return $currencyService->format($this->platform_fee, $this->currency ?? 'USD');
    }

    /**
     * Get the formatted artist royalty.
     */
    public function getFormattedSellerEarningsAttribute(): string
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        return $currencyService->format($this->seller_earnings, $this->currency ?? 'USD');
    }

    /**
     * Check if the transaction is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the transaction is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the transaction is a primary sale.
     */
    public function isPrimarySale(): bool
    {
        return $this->type === 'primary_sale';
    }

    /**
     * Check if the transaction is a resale.
     */
    public function isResale(): bool
    {
        return $this->type === 'resale';
    }

    /**
     * Complete the transaction.
     */
    public function complete(): bool
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Update artwork status
        $this->artwork->update(['status' => 'sold']);

        // Transfer ownership
        $currentOwnership = $this->artwork->ownerships()
            ->where('is_current_owner', true)
            ->first();

        if ($currentOwnership) {
            $currentOwnership->transferTo(
                $this->buyer,
                $this->amount,
                $this->type === 'resale' ? 'resale' : 'sale'
            );
        }

        return true;
    }

    /**
     * Generate a unique transaction ID.
     */
    public static function generateTransactionId(): string
    {
        do {
            $id = 'TXN-' . strtoupper(uniqid()) . '-' . rand(1000, 9999);
        } while (self::where('transaction_id', $id)->exists());
        
        return $id;
    }

    /**
     * Calculate platform fee based on configurable percentage.
     */
    public static function calculatePlatformFee(float $amount, ?float $percentage = null): float
    {
        // Use provided percentage or fall back to config
        $feePercentage = $percentage ?? (config('platform.fee_percentage', 10) / 100);
        
        $fee = round($amount * $feePercentage, 2);
        
        // Apply minimum fee if configured
        $minimumFee = config('platform.minimum_fee', 0);
        if ($minimumFee > 0 && $fee < $minimumFee) {
            $fee = $minimumFee;
        }
        
        // Apply maximum fee cap if configured
        $maximumFee = config('platform.maximum_fee', 0);
        if ($maximumFee > 0 && $fee > $maximumFee) {
            $fee = $maximumFee;
        }
        
        return $fee;
    }
    
    /**
     * Get the current platform fee percentage.
     */
    public static function getPlatformFeePercentage(): float
    {
        return config('platform.fee_percentage', 10);
    }

    /**
     * Calculate seller earnings.
     */
    public static function calculateSellerEarnings(float $amount, float $platformFee): float
    {
        return round($amount - $platformFee, 2);
    }

    /**
     * Scope a query to only include completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to filter by buyer.
     */
    public function scopeByBuyer($query, $buyerId)
    {
        return $query->where('buyer_id', $buyerId);
    }

    /**
     * Scope a query to filter by seller.
     */
    public function scopeBySeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
