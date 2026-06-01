<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'entity_type',
        'entity_id',
        'action',
        'old_values',
        'new_values',
        'changed_fields',
        'reason',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changed_fields' => 'array',
    ];

    /**
     * Get the user who made the change.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to filter by entity type.
     */
    public function scopeByEntityType($query, $entityType)
    {
        return $query->where('entity_type', $entityType);
    }

    /**
     * Scope a query to filter by entity.
     */
    public function scopeByEntity($query, $entityType, $entityId)
    {
        return $query->where('entity_type', $entityType)->where('entity_id', $entityId);
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by action.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Log a change to an entity.
     */
    public static function logChange(string $entityType, int $entityId, string $action, array $oldValues, array $newValues, string $reason = null): self
    {
        $changedFields = [];

        foreach ($oldValues as $key => $value) {
            if (!isset($newValues[$key]) || $newValues[$key] != $value) {
                $changedFields[] = $key;
            }
        }

        foreach ($newValues as $key => $value) {
            if (!isset($oldValues[$key])) {
                $changedFields[] = $key;
            }
        }

        return self::create([
            'user_id' => auth()->id(),
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'changed_fields' => $changedFields,
            'reason' => $reason,
        ]);
    }

    /**
     * Get the entity this log is for.
     */
    public function getEntityAttribute()
    {
        $modelClass = 'App\\Models\\' . $this->entity_type;
        if (class_exists($modelClass)) {
            return $modelClass::find($this->entity_id);
        }
        return null;
    }
}
