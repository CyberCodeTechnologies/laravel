<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'tracking_number',
        'carrier',
        'service',
        'shipping_cost',
        'currency',
        'status',
        'tracking_history',
        'shipped_at',
        'estimated_delivery',
        'delivered_at',
        'shipping_address',
        'notes',
        'label_url',
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'tracking_history' => 'array',
        'shipped_at' => 'datetime',
        'estimated_delivery' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInTransit($query)
    {
        return $query->whereIn('status', ['picked_up', 'in_transit', 'out_for_delivery']);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInTransit(): bool
    {
        return in_array($this->status, ['picked_up', 'in_transit', 'out_for_delivery']);
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function markAsShipped(): void
    {
        $this->update([
            'status' => 'picked_up',
            'shipped_at' => now(),
        ]);
    }

    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);

        // Update order status if needed
        if ($this->order) {
            $this->order->update(['status' => 'delivered']);
        }
    }

    public function addTrackingEvent(string $event, string $location = null, string $description = null): void
    {
        $history = $this->tracking_history ?? [];
        $history[] = [
            'event' => $event,
            'location' => $location,
            'description' => $description,
            'timestamp' => now()->toISOString(),
        ];
        $this->update(['tracking_history' => $history]);
    }

    public function getTrackingUrl(): ?string
    {
        $urls = [
            'DHL' => "https://www.dhl.com/en/express/tracking.html?AWB={$this->tracking_number}",
            'FedEx' => "https://www.fedex.com/apps/fedextrack/?tracknumbers={$this->tracking_number}",
            'UPS' => "https://www.ups.com/track?tracknum={$this->tracking_number}",
            'USPS' => "https://tools.usps.com/go/TrackConfirmAction?tLabels={$this->tracking_number}",
        ];

        return $urls[$this->carrier] ?? null;
    }
}
