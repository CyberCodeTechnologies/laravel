<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'email',
        'first_name',
        'last_name',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'total_amount',
        'shipping_cost',
        'subtotal_amount',
        'currency',
        'payment_method',
        'payment_method_id',
        'payment_status',
        'paid_at',
        'shipping_method',
        'status',
        'order_notes',
        'is_gift',
        'gift_message',
        'gift_receipt',
        // Custom artwork order fields
        'artist_id',
        'title',
        'description',
        'size',
        'medium',
        'style',
        'proposed_price',
        'reference_image',
        'customer_notes',
        'artist_notes',
        'order_type',
        'custom_status',
        'artist_accepted_at',
        'completed_at',
        'shipped_at',
        'delivered_at',
        // Additional workflow fields
        'on_hold_reason',
        'refund_amount',
        'refund_status',
        'refund_processed_at',
        'dispute_status',
        'dispute_reason',
        'dispute_resolved_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'subtotal_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'is_gift' => 'boolean',
        'gift_receipt' => 'boolean',
        'order_notes' => 'string',
        'gift_message' => 'string',
    ];

    /**
     * Get items in the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the user who placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the artist for custom artwork orders.
     */
    public function artist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    /**
     * Get the shipment for the order.
     */
    public function shipment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Shipment::class);
    }

    /**
     * Get payment proofs for the order.
     */
    public function paymentProofs(): HasMany
    {
        return $this->hasMany(PaymentProof::class);
    }

    /**
     * Get the commission for the order.
     */
    public function commission(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Commission::class);
    }

    /**
     * Get the formatted total amount.
     */
    public function getFormattedTotalAttribute(): string
    {
        $currencyService = app(\App\Services\CurrencyService::class);
        return $currencyService->format($this->total_amount, $this->currency);
    }

    /**
     * Get the full name.
     */
    public function getFullNameAttribute(): string
    {
        $firstName = is_string($this->first_name) ? trim($this->first_name) : '';
        $lastName = is_string($this->last_name) ? trim($this->last_name) : '';
        
        if (empty($firstName) && empty($lastName)) {
            return 'N/A';
        }
        
        return trim($firstName . ' ' . $lastName);
    }

    /**
     * Get the formatted order number.
     */
    public function getFormattedOrderNumberAttribute(): string
    {
        return '#' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Check if order can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'paid', 'processing']);
    }

    /**
     * Check if order can be refunded.
     */
    public function canBeRefunded(): bool
    {
        return in_array($this->status, ['paid', 'shipped', 'delivered']);
    }

    /**
     * Update order status.
     */
    public function updateStatus(string $status): void
    {
        $this->update(['status' => $status]);
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'paid' => 'Paid',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded',
            'failed' => 'Payment Failed',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Check if order is a custom artwork order.
     */
    public function getIsCustomOrderAttribute(): bool
    {
        return $this->order_type === 'custom';
    }

    /**
     * Get status color.
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'pending' => 'yellow',
            'processing' => 'blue',
            'paid' => 'green',
            'shipped' => 'purple',
            'delivered' => 'green',
            'cancelled' => 'red',
            'refunded' => 'orange',
            'failed' => 'red',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    /**
     * Check if this is a custom artwork order.
     */
    public function isCustomOrder(): bool
    {
        return $this->order_type === 'custom';
    }

    /**
     * Get custom status label.
     */
    public function getCustomStatusLabelAttribute(): string
    {
        $labels = [
            'pending_artist_approval' => 'Pending Artist Approval',
            'artist_accepted' => 'Artist Accepted',
            'artist_rejected' => 'Artist Rejected',
            'in_progress' => 'In Progress',
            'ready_for_review' => 'Ready for Review',
            'customer_approved' => 'Customer Approved',
            'customer_rejected' => 'Customer Rejected',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ];

        return $labels[$this->custom_status] ?? ucfirst($this->custom_status);
    }

    /**
     * Get custom status color.
     */
    public function getCustomStatusColorAttribute(): string
    {
        $colors = [
            'pending_artist_approval' => 'yellow',
            'artist_accepted' => 'green',
            'artist_rejected' => 'red',
            'in_progress' => 'blue',
            'ready_for_review' => 'purple',
            'customer_approved' => 'green',
            'customer_rejected' => 'red',
            'shipped' => 'indigo',
            'delivered' => 'green',
            'cancelled' => 'red',
        ];

        return $colors[$this->custom_status] ?? 'gray';
    }

    /**
     * Check if artist can accept this order.
     */
    public function canArtistAccept(): bool
    {
        return $this->isCustomOrder() && $this->custom_status === 'pending_artist_approval';
    }

    /**
     * Check if artist can reject this order.
     */
    public function canArtistReject(): bool
    {
        return $this->isCustomOrder() && $this->custom_status === 'pending_artist_approval';
    }

    /**
     * Check if order can be marked as in progress.
     */
    public function canStartProgress(): bool
    {
        return $this->isCustomOrder() && $this->custom_status === 'artist_accepted';
    }

    /**
     * Check if order can be marked as ready for review.
     */
    public function canMarkReadyForReview(): bool
    {
        return $this->isCustomOrder() && $this->custom_status === 'in_progress';
    }

    /**
     * Check if customer can approve the artwork.
     */
    public function canCustomerApprove(): bool
    {
        return $this->isCustomOrder() && $this->custom_status === 'ready_for_review';
    }

    /**
     * Check if customer can reject the artwork.
     */
    public function canCustomerReject(): bool
    {
        return $this->isCustomOrder() && $this->custom_status === 'ready_for_review';
    }

    /**
     * Update custom status with timestamp.
     */
    public function updateCustomStatus(string $status): void
    {
        $this->update([
            'custom_status' => $status,
            match($status) {
                'artist_accepted' => 'artist_accepted_at',
                'ready_for_review' => 'completed_at',
                'shipped' => 'shipped_at',
                'delivered' => 'delivered_at',
                default => null
            } => now()
        ]);
    }

    /**
     * Get reference image URL.
     */
    public function getReferenceImageUrlAttribute(): string
    {
        return $this->reference_image ? asset('storage/' . $this->reference_image) : '';
    }

    /**
     * Scope for custom orders only.
     */
    public function scopeCustom($query)
    {
        return $query->where('order_type', 'custom');
    }

    /**
     * Scope for orders by specific artist.
     */
    public function scopeForArtist($query, $artistId)
    {
        return $query->where('artist_id', $artistId);
    }

    /**
     * Scope for orders with specific custom status.
     */
    public function scopeWithCustomStatus($query, $status)
    {
        return $query->where('custom_status', $status);
    }
}
