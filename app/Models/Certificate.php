<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'artwork_id',
        'artist_id',
        'certificate_code',
        'qr_code',
        'artist_signature',
        'panchi_signature',
        'issue_date',
        'certificate_text',
        'certificate_pdf',
        'is_verified',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the artwork for this certificate.
     */
    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artwork::class);
    }

    /**
     * Get the artist who issued this certificate.
     */
    public function artist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    /**
     * Get the QR code URL.
     */
    public function getQrCodeUrlAttribute(): string
    {
        return $this->qr_code 
            ? asset('storage/' . $this->qr_code) 
            : '';
    }

    /**
     * Get the certificate PDF URL.
     */
    public function getCertificatePdfUrlAttribute(): string
    {
        return $this->certificate_pdf 
            ? asset('storage/' . $this->certificate_pdf) 
            : '';
    }

    /**
     * Get the verification URL.
     */
    public function getVerificationUrlAttribute(): string
    {
        return route('verify.certificate', $this->certificate_code);
    }

    /**
     * Generate a unique certificate code.
     */
    public static function generateCertificateCode(): string
    {
        do {
            $code = 'PG-' . strtoupper(uniqid()) . '-' . rand(1000, 9999);
        } while (self::where('certificate_code', $code)->exists());
        
        return $code;
    }

    /**
     * Check if the certificate is authentic.
     */
    public function isAuthentic(): bool
    {
        return $this->is_verified && $this->artwork && $this->artist;
    }

    /**
     * Get the current owner of the artwork.
     */
    public function getCurrentOwnerAttribute(): ?User
    {
        return $this->artwork?->current_owner;
    }

    /**
     * Get the ownership history.
     */
    public function getOwnershipHistoryAttribute(): array
    {
        if (!$this->artwork) {
            return [];
        }

        return $this->artwork->ownerships()
            ->with('owner')
            ->orderBy('acquired_at', 'asc')
            ->get()
            ->map(function ($ownership) {
                return [
                    'owner' => $ownership->owner,
                    'acquired_at' => $ownership->acquired_at,
                    'purchase_price' => $ownership->purchase_price,
                    'transaction_type' => $ownership->transaction_type,
                ];
            })
            ->toArray();
    }
}
