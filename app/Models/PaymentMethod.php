<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'is_active',
        'requires_manual_verification',
        'config',
        'instructions',
        'logo',
        'qr_code',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_manual_verification' => 'boolean',
        'config' => 'array',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeManualVerification($query)
    {
        return $query->where('requires_manual_verification', true);
    }

    public function scopeAutomatic($query)
    {
        return $query->where('requires_manual_verification', false);
    }
}
