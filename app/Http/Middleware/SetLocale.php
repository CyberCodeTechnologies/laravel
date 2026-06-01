<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\GeneralSetting;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if locale is set in session (highest priority)
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        // Check if locale is set in cookie
        elseif ($request->cookie('locale')) {
            App::setLocale($request->cookie('locale'));
            // Also store in session for consistency
            Session::put('locale', $request->cookie('locale'));
        }
        // Check database for default language setting
        elseif (class_exists('App\Models\GeneralSetting')) {
            try {
                $defaultLanguage = GeneralSetting::getValue('default_language', config('app.locale', 'en'));
                $autoDetect = GeneralSetting::getValue('auto_detect_language', '0');
                
                if ($autoDetect === '1' && $request->header('Accept-Language')) {
                    // Auto-detect from browser if enabled
                    $locale = substr($request->header('Accept-Language'), 0, 2);
                    if (in_array($locale, ['en', 'my'])) {
                        App::setLocale($locale);
                        Session::put('locale', $locale);
                    } else {
                        // Fall back to database default
                        App::setLocale($defaultLanguage);
                        Session::put('locale', $defaultLanguage);
                    }
                } else {
                    // Use database default
                    App::setLocale($defaultLanguage);
                    Session::put('locale', $defaultLanguage);
                }
            } catch (\Exception $e) {
                // Fallback to config if database fails
                App::setLocale(config('app.locale', 'en'));
            }
        }
        // Check browser preference as final fallback
        elseif ($request->header('Accept-Language')) {
            $locale = substr($request->header('Accept-Language'), 0, 2);
            if (in_array($locale, ['en', 'my'])) {
                App::setLocale($locale);
                Session::put('locale', $locale);
            }
        }

        return $next($request);
    }
}
