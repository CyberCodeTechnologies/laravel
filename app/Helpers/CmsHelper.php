<?php

if (!function_exists('cms_content')) {
    /**
     * Get CMS content by key with fallback to language file
     *
     * @param string $key Content key
     * @param string|null $default Default value if not found
     * @return string
     */
    function cms_content(string $key, ?string $default = null): string
    {
        $content = \App\Models\PageContent::getByKey($key);
        
        if ($content) {
            return $content;
        }
        
        // Fallback to language file
        $langKey = str_replace('_', '.', $key);
        $translated = __($langKey);
        
        // Return translated if it's not the same as the key (meaning translation exists)
        if ($translated !== $langKey) {
            return $translated;
        }
        
        return $default ?? $key;
    }
}

if (!function_exists('cms_content_or')) {
    /**
     * Get CMS content by key with custom fallback
     *
     * @param string $key Content key
     * @param string $fallback Fallback value
     * @return string
     */
    function cms_content_or(string $key, string $fallback): string
    {
        return cms_content($key, $fallback);
    }
}

if (!function_exists('cms_has_content')) {
    /**
     * Check if CMS content exists for a key
     *
     * @param string $key Content key
     * @return bool
     */
    function cms_has_content(string $key): bool
    {
        return \App\Models\PageContent::where('key', $key)->active()->exists();
    }
}

if (!function_exists('cms_page_content')) {
    /**
     * Get all content for a specific page
     *
     * @param string $page Page name
     * @param string|null $section Section name (optional)
     * @return \Illuminate\Support\Collection
     */
    function cms_page_content(string $page, ?string $section = null): \Illuminate\Support\Collection
    {
        $query = \App\Models\PageContent::forPage($page)->active();
        
        if ($section) {
            $query->forSection($section);
        }
        
        return $query->orderBy('sort_order')->get();
    }
}

if (!function_exists('general_setting')) {
    /**
     * Get a general setting value by key
     *
     * @param string $key Setting key
     * @param mixed $default Default value if not found
     * @return mixed
     */
    function general_setting(string $key, $default = null)
    {
        return \App\Models\GeneralSetting::getValue($key, $default);
    }
}

if (!function_exists('cms_is_active')) {
    /**
     * Check if CMS content is active
     *
     * @param string $key Content key
     * @param bool $default Default if not found in DB
     * @return bool
     */
    function cms_is_active(string $key, bool $default = true): bool
    {
        $content = \App\Models\PageContent::where('key', $key)->first();
        
        if ($content) {
            return (bool) $content->is_active;
        }
        
        return $default;
    }
}

if (!function_exists('general_setting_image')) {
    /**
     * Get a general setting image URL
     *
     * @param string $key Setting key
     * @param string|null $default Default image URL if not found
     * @return string|null
     */
    function general_setting_image(string $key, ?string $default = null): ?string
    {
        $setting = \App\Models\GeneralSetting::where('key', $key)->active()->first();
        
        if ($setting && $setting->type === 'image' && $setting->value) {
            return $setting->image_url;
        }
        
        return $default;
    }
}
