<?php

namespace App\Helpers {

use Illuminate\Support\Facades\Route;

class AdminRouteHelper
{
    public static function usesLegacyPublicPrefix(): bool
    {
        if (self::appUrlIncludesPublic()) {
            return false;
        }

        return str_contains(request()->getRequestUri(), '/public/admin');
    }

    public static function requestHasDoublePublicPrefix(): bool
    {
        return str_contains(request()->getRequestUri(), '/public/public/admin');
    }

    protected static function appUrlIncludesPublic(): bool
    {
        $appUrl = config('app.url') ?? env('APP_URL');

        if (empty($appUrl)) {
            return false;
        }

        return str_contains($appUrl, '/public');
    }

    /**
     * Resolve an admin named route without doubling the /public segment in URLs.
     */
    public static function url(string $name, array $params = []): string
    {
        $relative = route($name, $params, false);

        if (! str_starts_with($relative, '/admin')) {
            $url = route($name, $params);
            return str_replace('/public/public', '/public', $url);
        }

        if (self::requestHasDoublePublicPrefix()) {
            $url = url('/public/public'.$relative);
            return str_replace('/public/public', '/public', $url);
        }

        if (self::usesLegacyPublicPrefix()) {
            $url = url('/public'.$relative);
            return str_replace('/public/public', '/public', $url);
        }

        $url = route($name, $params);
        return str_replace('/public/public', '/public', $url);
    }
}

}

namespace {
    if (! function_exists('admin_route')) {
        function admin_route(string $name, array $params = []): string
        {
            return \App\Helpers\AdminRouteHelper::url($name, $params);
        }
    }
}

