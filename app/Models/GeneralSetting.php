<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the value attribute, ensuring it's always a string for display.
     * JSON values are stored as strings and returned as strings.
     */
    public function getValueAttribute($value)
    {
        if (is_array($value)) {
            return json_encode($value);
        }

        return $value;
    }

    /**
     * Scope a query to only include active settings.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by group.
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Get a setting value by key.
     */
    public static function getValue(string $key, $default = null)
    {
        try {
            $setting = self::where('key', $key)->active()->first();
            return $setting ? $setting->value : $default;
        } catch (\Throwable $e) {
            // Log the exception at debug level and return the default value so the application
            // can continue to respond instead of throwing a 500 when the settings table
            // is missing or the database is unavailable.
            if (function_exists('logger')) {
                logger()->debug('GeneralSetting::getValue fallback to default due to exception', [
                    'key' => $key,
                    'error' => $e->getMessage(),
                ]);
            }

            return $default;
        }
    }

    /**
     * Set a setting value by key.
     */
    public static function setValue(string $key, $value, string $type = 'text', string $group = 'general')
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
            ]
        );
    }

    /**
     * Get all settings as key-value pairs.
     */
    public static function getAllAsArray()
    {
        try {
            return self::active()->pluck('value', 'key')->toArray();
        } catch (\Throwable $e) {
            if (function_exists('logger')) {
                logger()->debug('GeneralSetting::getAllAsArray failed; returning empty array', [
                    'error' => $e->getMessage(),
                ]);
            }

            return [];
        }
    }

    /**
     * Get image URL for image-type settings.
     */
    public function getImageUrlAttribute()
    {
        if ($this->type !== 'image' || empty($this->value)) {
            return null;
        }

        if (filter_var($this->value, FILTER_VALIDATE_URL)) {
            return $this->value;
        }

        // Check if we're running through XAMPP with /public/ prefix
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        if (strpos($requestUri, '/panchigallery.com/public/') !== false) {
            return '/panchigallery.com/public/storage/' . $this->value;
        }

        return asset('storage/' . $this->value);
    }
}
