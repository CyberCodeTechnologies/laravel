<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'artist_id',
        'amount',
        'currency',
        'status',
        'method',
        'payment_reference',
        'commission_ids',
        'commission_count',
        'notes',
        'failure_reason',
        'requested_at',
        'processed_at',
        'processed_by',
        // Additional workflow fields
        'scheduled_at',
        'on_hold_reason',
        'tax_review_status',
        'tax_amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_ids' => 'array',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function artist()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function commissions()
    {
        if ($this->commission_ids) {
            return Commission::whereIn('id', $this->commission_ids)->get();
        }
        return collect();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForArtist($query, $artistId)
    {
        return $query->where('artist_id', $artistId);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function markAsProcessing(): void
    {
        $this->update(['status' => 'processing']);
    }

    public function markAsCompleted(string $paymentReference = null): void
    {
        $this->update([
            'status' => 'completed',
            'payment_reference' => $paymentReference,
            'processed_at' => now(),
        ]);

        // Mark all associated commissions as paid
        if ($this->commission_ids) {
            Commission::whereIn('id', $this->commission_ids)
                ->where('status', 'pending')
                ->update(['status' => 'paid', 'paid_at' => now()]);
        }
    }

    public function markAsFailed(string $reason): void
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
            'processed_at' => now(),
        ]);
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isOnHold(): bool
    {
        return $this->status === 'on_hold';
    }

    public function isUnderTaxReview(): bool
    {
        return $this->tax_review_status === 'under_review';
    }

    public function schedule(\Carbon\Carbon $scheduledAt): void
    {
        $this->update([
            'status' => 'scheduled',
            'scheduled_at' => $scheduledAt,
        ]);
    }

    public function putOnHold(string $reason): void
    {
        $this->update([
            'status' => 'on_hold',
            'on_hold_reason' => $reason,
        ]);
    }

    public function releaseFromHold(): void
    {
        $this->update([
            'status' => 'pending',
            'on_hold_reason' => null,
        ]);
    }

    public function startTaxReview(float $taxAmount): void
    {
        $this->update([
            'tax_review_status' => 'under_review',
            'tax_amount' => $taxAmount,
        ]);
    }

    public function completeTaxReview(): void
    {
        $this->update([
            'tax_review_status' => 'approved',
        ]);
    }
}
