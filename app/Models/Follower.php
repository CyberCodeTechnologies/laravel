<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follower extends Model
{
    use HasFactory;

    protected $fillable = [
        'follower_id',
        'following_id',
    ];

    /**
     * Get the user who is following.
     */
    public function follower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * Get the user who is being followed.
     */
    public function following(): BelongsTo
    {
        return $this->belongsTo(User::class, 'following_id');
    }

    /**
     * Scope a query to filter by follower.
     */
    public function scopeByFollower($query, $followerId)
    {
        return $query->where('follower_id', $followerId);
    }

    /**
     * Scope a query to filter by following.
     */
    public function scopeByFollowing($query, $followingId)
    {
        return $query->where('following_id', $followingId);
    }
}
