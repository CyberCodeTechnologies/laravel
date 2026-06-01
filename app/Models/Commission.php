<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'order_id',
        'artist_id',
        'sale_amount',
        'platform_fee',
        'artist_earnings',
        'platform_fee_percentage',
        'status',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'sale_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'artist_earnings' => 'decimal:2',
        'platform_fee_percentage' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function artist()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeForArtist($query, $artistId)
    {
        return $query->where('artist_id', $artistId);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public static function calculatePlatformFee(float $amount, float $percentage = 30.00): float
    {
        return round($amount * ($percentage / 100), 2);
    }

    public static function calculateArtistEarnings(float $amount, float $platformFee): float
    {
        return round($amount - $platformFee, 2);
    }
}
