<?php

namespace App\Services;

use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class ImageSyncService
{
    /**
     * Sync all image settings and ensure they're properly cached
     */
    public static function syncAllImages(): array
    {
        $imageSettings = GeneralSetting::where('type', 'image')->active()->get();
        $syncResults = [];

        foreach ($imageSettings as $setting) {
            $syncResults[$setting->key] = self::syncImage($setting);
        }

        // Clear image cache
        self::clearImageCache();

        return $syncResults;
    }

    /**
     * Sync a single image setting
     */
    public static function syncImage(GeneralSetting $setting): array
    {
        $result = [
            'key' => $setting->key,
            'status' => 'success',
            'message' => 'Image synchronized successfully',
            'url' => null,
            'fallback_used' => false
        ];

        try {
            if (empty($setting->value)) {
                $result['status'] = 'warning';
                $result['message'] = 'No image uploaded, using fallback';
                $result['url'] = self::getFallbackImage($setting->key);
                $result['fallback_used'] = true;
            } else {
                // Verify the image exists in storage
                if (!filter_var($setting->value, FILTER_VALIDATE_URL)) {
                    if (!Storage::disk('public')->exists($setting->value)) {
                        $result['status'] = 'error';
                        $result['message'] = 'Image file not found in storage';
                        $result['url'] = self::getFallbackImage($setting->key);
                        $result['fallback_used'] = true;
                    } else {
                        // Generate correct URL based on environment
                        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
                        if (strpos($requestUri, '/panchigallery.com/public/') !== false) {
                            $result['url'] = '/panchigallery.com/public/storage/' . $setting->value;
                        } else {
                            $result['url'] = $setting->image_url;
                        }
                    }
                } else {
                    $result['url'] = $setting->value;
                }
            }

            // Cache the result
            $cacheKey = "image_sync_{$setting->key}";
            Cache::put($cacheKey, $result, 3600); // Cache for 1 hour

        } catch (\Exception $e) {
            $result['status'] = 'error';
            $result['message'] = 'Sync failed: ' . $e->getMessage();
            $result['url'] = self::getFallbackImage($setting->key);
            $result['fallback_used'] = true;
        }

        return $result;
    }

    /**
     * Get fallback image URL for a setting key
     */
    private static function getFallbackImage(string $key): string
    {
        // Check if we're running through XAMPP with /public/ prefix
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        $isXampp = strpos($requestUri, '/panchigallery.com/public/') !== false;
        
        $fallbacks = [
            'site_logo' => $isXampp ? '/panchigallery.com/public/images/logo.png' : asset('images/logo.png'),
            'site_favicon' => $isXampp ? '/panchigallery.com/public/favicon.png' : asset('favicon.png'),
            'site_og_image' => $isXampp ? '/panchigallery.com/public/images/og-default.jpg' : asset('images/og-default.jpg'),
            'hero_background_image' => $isXampp ? '/panchigallery.com/public/images/placeholder-artwork.jpg' : asset('images/placeholder-artwork.jpg'),
            'flash_sale_banner_image' => $isXampp ? '/panchigallery.com/public/images/placeholder-artwork.jpg' : asset('images/placeholder-artwork.jpg'),
            'about_hero_background' => $isXampp ? '/panchigallery.com/public/images/placeholder-artwork.jpg' : asset('images/placeholder-artwork.jpg'),
            'about_mission_image' => $isXampp ? '/panchigallery.com/public/images/placeholder-artwork.jpg' : asset('images/placeholder-artwork.jpg'),
            'about_section_image' => $isXampp ? '/panchigallery.com/public/images/placeholder-artwork.jpg' : asset('images/placeholder-artwork.jpg'),
            'home_hero_background' => $isXampp ? '/panchigallery.com/public/images/placeholder-artwork.jpg' : asset('images/placeholder-artwork.jpg'),
            'contact_hero_background' => $isXampp ? '/panchigallery.com/public/images/placeholder-artwork.jpg' : asset('images/placeholder-artwork.jpg'),
            'artists_hero_background' => $isXampp ? '/panchigallery.com/public/images/placeholder-artwork.jpg' : asset('images/placeholder-artwork.jpg'),
            'artworks_hero_background' => $isXampp ? '/panchigallery.com/public/images/placeholder-artwork.jpg' : asset('images/placeholder-artwork.jpg'),
            'secure_payment_badge_image' => $isXampp ? '/panchigallery.com/public/images/logo.png' : asset('images/logo.png'),
        ];

        return $fallbacks[$key] ?? ($isXampp ? '/panchigallery.com/public/images/placeholder-artwork.jpg' : asset('images/placeholder-artwork.jpg'));
    }

    /**
     * Get synchronized image URL with caching
     */
    public static function getSynchronizedImage(string $key, ?string $default = null): string
    {
        $cacheKey = "image_sync_{$key}";
        
        if (Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            return $cached['url'] ?? $default ?? self::getFallbackImage($key);
        }

        // Sync the image if not cached
        $setting = GeneralSetting::where('key', $key)->active()->first();
        if ($setting) {
            $result = self::syncImage($setting);
            return $result['url'];
        }

        return $default ?? self::getFallbackImage($key);
    }

    /**
     * Clear image cache
     */
    public static function clearImageCache(): void
    {
        $imageSettings = GeneralSetting::where('type', 'image')->active()->pluck('key');
        
        foreach ($imageSettings as $key) {
            Cache::forget("image_sync_{$key}");
        }
        
        Cache::forget('all_image_sync_results');
    }

    /**
     * Get all image sync results (cached)
     */
    public static function getAllSyncResults(): array
    {
        return Cache::remember('all_image_sync_results', 3600, function () {
            return self::syncAllImages();
        });
    }

    /**
     * Validate image file
     */
    public static function validateImage($file): array
    {
        $validation = [
            'valid' => true,
            'errors' => []
        ];

        // Check if file is actually an image
        if (!$file->isValid() || !str_starts_with($file->getMimeType(), 'image/')) {
            $validation['valid'] = false;
            $validation['errors'][] = 'File must be a valid image';
        }

        // Check file size (max 2MB)
        if ($file->getSize() > 2 * 1024 * 1024) {
            $validation['valid'] = false;
            $validation['errors'][] = 'Image size must be less than 2MB';
        }

        // Check image dimensions
        try {
            $imageInfo = getimagesize($file->getPathname());
            if ($imageInfo === false) {
                $validation['valid'] = false;
                $validation['errors'][] = 'Unable to read image dimensions';
            }
        } catch (\Exception $e) {
            $validation['valid'] = false;
            $validation['errors'][] = 'Invalid image file';
        }

        return $validation;
    }

    /**
     * Get image optimization suggestions
     */
    public static function getOptimizationSuggestions(string $key): array
    {
        $setting = GeneralSetting::where('key', $key)->active()->first();
        if (!$setting || empty($setting->value)) {
            return [];
        }

        $suggestions = [];
        $filePath = storage_path('app/public/' . $setting->value);

        if (file_exists($filePath)) {
            $fileSize = filesize($filePath);
            $imageInfo = getimagesize($filePath);

            // Check file size
            if ($fileSize > 500 * 1024) { // > 500KB
                $suggestions[] = 'Consider compressing this image to reduce file size';
            }

            // Check dimensions
            if ($imageInfo) {
                $width = $imageInfo[0];
                $height = $imageInfo[1];

                if ($key === 'site_favicon' && ($width > 64 || $height > 64)) {
                    $suggestions[] = 'Favicon should be 32x32 or 64x64 pixels for best performance';
                }

                if ($key === 'site_logo' && $width > 300) {
                    $suggestions[] = 'Logo width should not exceed 300px for better performance';
                }

                if ($key === 'site_og_image' && ($width !== 1200 || $height !== 630)) {
                    $suggestions[] = 'OG image should be 1200x630 pixels for optimal social media display';
                }
            }
        }

        return $suggestions;
    }
}
