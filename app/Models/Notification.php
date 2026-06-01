<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'is_read',
        'read_at',
        'metadata',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the user who owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to filter unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to filter read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Create a notification for a user.
     */
    public static function createForUser(int $userId, string $type, string $title, string $message, string $link = null, array $metadata = []): self
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => false,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Create a notification for multiple users.
     */
    public static function createForUsers(array $userIds, string $type, string $title, string $message, string $link = null, array $metadata = []): void
    {
        foreach ($userIds as $userId) {
            self::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'is_read' => false,
                'metadata' => $metadata,
            ]);
        }
    }

    /**
     * Notification types constants.
     */
    public const TYPE_ARTWORK_APPROVED = 'artwork_approved';
    public const TYPE_ARTWORK_REJECTED = 'artwork_rejected';
    public const TYPE_ARTWORK_SOLD = 'artwork_sold';
    public const TYPE_ORDER_CREATED = 'order_created';
    public const TYPE_ORDER_SHIPPED = 'order_shipped';
    public const TYPE_ORDER_DELIVERED = 'order_delivered';
    public const TYPE_PAYMENT_RECEIVED = 'payment_received';
    public const TYPE_PAYOUT_PROCESSED = 'payout_processed';
    public const TYPE_NEW_FOLLOWER = 'new_follower';
    public const TYPE_NEW_LIKE = 'new_like';
    public const TYPE_CUSTOM_ORDER_REQUEST = 'custom_order_request';
    public const TYPE_CUSTOM_ORDER_ACCEPTED = 'custom_order_accepted';
    public const TYPE_CUSTOM_ORDER_COMPLETED = 'custom_order_completed';
    public const TYPE_RESALE_APPROVED = 'resale_approved';
    public const TYPE_RESALE_SOLD = 'resale_sold';
    public const TYPE_SYSTEM_ANNOUNCEMENT = 'system_announcement';
}
