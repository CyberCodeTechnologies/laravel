<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'transaction_reference',
        'screenshot',
        'notes',
        'status',
        'admin_notes',
        'verified_at',
        'verified_by',
        // Additional workflow fields
        'expired_at',
        'fraud_flag',
        'fraud_notes',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isExpired(): bool
    {
        return $this->expired_at && now()->gt($this->expired_at);
    }

    public function isUnderReview(): bool
    {
        return $this->status === 'under_review';
    }

    public function isFlagged(): bool
    {
        return $this->fraud_flag === true;
    }

    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }

    public function markAsUnderReview(): void
    {
        $this->update(['status' => 'under_review']);
    }

    public function flagAsFraud(string $notes = null): void
    {
        $this->update([
            'fraud_flag' => true,
            'fraud_notes' => $notes,
            'status' => 'under_review',
        ]);
    }

    public function clearFraudFlag(): void
    {
        $this->update([
            'fraud_flag' => false,
            'fraud_notes' => null,
        ]);
    }
}
