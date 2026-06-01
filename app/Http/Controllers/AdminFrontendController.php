<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use App\Models\PageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminFrontendController extends Controller
{
    /**
     * Display frontend management dashboard
     */
    public function dashboard()
    {
        $homepageSections = PageContent::forPage('home')
            ->select('section')
            ->distinct()
            ->pluck('section');
            
        $footerSettings = GeneralSetting::byGroup('footer')->get();
        $socialSettings = GeneralSetting::byGroup('social')->get();
        $themeSettings = GeneralSetting::byGroup('theme')->get();
        $seoSettings = GeneralSetting::byGroup('seo')->get();
        
        return view('admin.frontend.dashboard', compact(
            'homepageSections',
            'footerSettings',
            'socialSettings',
            'themeSettings',
            'seoSettings'
        ));
    }

    /**
     * Manage homepage sections
     */
    public function homepage()
    {
        $sections = [
            'hero' => PageContent::forPage('home')->forSection('hero')->active()->orderBy('sort_order')->get(),
            'featured' => PageContent::forPage('home')->forSection('featured')->active()->orderBy('sort_order')->get(),
            'about' => PageContent::forPage('home')->forSection('about')->active()->orderBy('sort_order')->get(),
            'artists' => PageContent::forPage('home')->forSection('artists')->active()->orderBy('sort_order')->get(),
            'categories' => PageContent::forPage('home')->forSection('categories')->active()->orderBy('sort_order')->get(),
            'testimonials' => PageContent::forPage('home')->forSection('testimonials')->active()->orderBy('sort_order')->get(),
            'cta' => PageContent::forPage('home')->forSection('cta')->active()->orderBy('sort_order')->get(),
        ];
        
        return view('admin.frontend.homepage', compact('sections'));
    }

    /**
     * Update homepage section
     */
    public function updateHomepageSection(Request $request, string $section)
    {
        $validated = $request->validate([
            'contents' => 'required|array',
            'contents.*.key' => 'required|string',
            'contents.*.content_en' => 'nullable|string',
            'contents.*.content_my' => 'nullable|string',
            'contents.*.is_active' => 'boolean',
        ]);

        foreach ($validated['contents'] as $contentData) {
            PageContent::updateOrCreate(
                [
                    'page' => 'home',
                    'section' => $section,
                    'key' => $contentData['key'],
                ],
                [
                    'content_en' => $contentData['content_en'] ?? '',
                    'content_my' => $contentData['content_my'] ?? null,
                    'type' => $contentData['type'] ?? 'text',
                    'is_active' => $contentData['is_active'] ?? true,
                    'sort_order' => $contentData['sort_order'] ?? 0,
                ]
            );
        }

        // Clear cache
        $this->clearFrontendCache();

        return back()->with('success', ucfirst($section) . ' section updated successfully.');
    }

    /**
     * Manage navigation menu
     */
    public function navigation()
    {
        $menuItems = PageContent::forPage('navigation')
            ->active()
            ->orderBy('sort_order')
            ->get();
            
        return view('admin.frontend.navigation', compact('menuItems'));
    }

    /**
     * Update navigation menu
     */
    public function updateNavigation(Request $request)
    {
        $validated = $request->validate([
            'menu_items' => 'required|array',
            'menu_items.*.key' => 'required|string',
            'menu_items.*.content_en' => 'required|string',
            'menu_items.*.content_my' => 'nullable|string',
            'menu_items.*.sort_order' => 'integer',
            'menu_items.*.is_active' => 'boolean',
        ]);

        foreach ($validated['menu_items'] as $item) {
            PageContent::updateOrCreate(
                [
                    'page' => 'navigation',
                    'section' => 'main',
                    'key' => $item['key'],
                ],
                [
                    'content_en' => $item['content_en'],
                    'content_my' => $item['content_my'] ?? null,
                    'type' => 'text',
                    'sort_order' => $item['sort_order'] ?? 0,
                    'is_active' => $item['is_active'] ?? true,
                ]
            );
        }

        $this->clearFrontendCache();

        return back()->with('success', 'Navigation menu updated successfully.');
    }

    /**
     * Manage footer content
     */
    public function footer()
    {
        $footerColumns = [
            'column_1' => GeneralSetting::byGroup('footer')->where('key', 'like', 'footer_column_1%')->get(),
            'column_2' => GeneralSetting::byGroup('footer')->where('key', 'like', 'footer_column_2%')->get(),
            'column_3' => GeneralSetting::byGroup('footer')->where('key', 'like', 'footer_column_3%')->get(),
            'column_4' => GeneralSetting::byGroup('footer')->where('key', 'like', 'footer_column_4%')->get(),
        ];
        
        $footerBottom = GeneralSetting::byGroup('footer')->where('key', 'like', 'footer_bottom%')->get();
        
        return view('admin.frontend.footer', compact('footerColumns', 'footerBottom'));
    }

    /**
     * Update footer content
     */
    public function updateFooter(Request $request)
    {
        $validated = $request->validate([
            'footer' => 'required|array',
            'footer.*.key' => 'required|string',
            'footer.*.value' => 'nullable|string',
            'footer.*.value_my' => 'nullable|string',
        ]);

        foreach ($validated['footer'] as $setting) {
            GeneralSetting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'] ?? '',
                    'value_my' => $setting['value_my'] ?? null,
                    'group' => 'footer',
                    'type' => 'text',
                    'is_active' => true,
                ]
            );
        }

        $this->clearFrontendCache();

        return back()->with('success', 'Footer content updated successfully.');
    }

    /**
     * Manage theme/appearance
     */
    public function theme()
    {
        $themeSettings = GeneralSetting::byGroup('theme')->get()->keyBy('key');
        
        $defaultColors = [
            'primary_color' => '#000000',
            'secondary_color' => '#ffffff',
            'accent_color' => '#d4af37',
            'text_color' => '#171717',
            'background_color' => '#ffffff',
        ];
        
        return view('admin.frontend.theme', compact('themeSettings', 'defaultColors'));
    }

    /**
     * Update theme settings
     */
    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|array',
            'theme.primary_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'theme.secondary_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'theme.accent_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'theme.font_family' => 'required|string|in:inter,playfair,roboto',
            'theme.border_radius' => 'required|string|in:none,sm,md,lg,xl',
        ]);

        foreach ($validated['theme'] as $key => $value) {
            GeneralSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => 'theme',
                    'type' => is_string($value) && Str::startsWith($value, '#') ? 'color' : 'text',
                    'is_active' => true,
                ]
            );
        }

        // Regenerate CSS
        $this->regenerateThemeCSS();
        $this->clearFrontendCache();

        return back()->with('success', 'Theme settings updated successfully.');
    }

    /**
     * Manage SEO settings
     */
    public function seo()
    {
        $seoSettings = GeneralSetting::byGroup('seo')->get()->keyBy('key');
        
        return view('admin.frontend.seo', compact('seoSettings'));
    }

    /**
     * Update SEO settings
     */
    public function updateSeo(Request $request)
    {
        $validated = $request->validate([
            'seo' => 'required|array',
            'seo.meta_title' => 'required|string|max:60',
            'seo.meta_description' => 'required|string|max:160',
            'seo.meta_keywords' => 'nullable|string',
            'seo.og_image' => 'nullable|string',
            'seo.canonical_url' => 'nullable|url',
            'seo.robots' => 'required|string|in:index,follow,noindex,nofollow',
            'seo.google_analytics' => 'nullable|string',
            'seo.facebook_pixel' => 'nullable|string',
        ]);

        foreach ($validated['seo'] as $key => $value) {
            GeneralSetting::updateOrCreate(
                ['key' => 'seo_' . $key],
                [
                    'value' => $value ?? '',
                    'group' => 'seo',
                    'type' => 'text',
                    'is_active' => true,
                ]
            );
        }

        $this->clearFrontendCache();

        return back()->with('success', 'SEO settings updated successfully.');
    }

    /**
     * Manage social media links
     */
    public function social()
    {
        $socialLinks = GeneralSetting::byGroup('social')->get();
        
        return view('admin.frontend.social', compact('socialLinks'));
    }

    /**
     * Update social media links
     */
    public function updateSocial(Request $request)
    {
        $validated = $request->validate([
            'social' => 'required|array',
            'social.facebook' => 'nullable|url',
            'social.instagram' => 'nullable|url',
            'social.twitter' => 'nullable|url',
            'social.youtube' => 'nullable|url',
            'social.linkedin' => 'nullable|url',
            'social.pinterest' => 'nullable|url',
        ]);

        foreach ($validated['social'] as $platform => $url) {
            GeneralSetting::updateOrCreate(
                ['key' => 'social_' . $platform],
                [
                    'value' => $url ?? '',
                    'group' => 'social',
                    'type' => 'url',
                    'is_active' => !empty($url),
                ]
            );
        }

        $this->clearFrontendCache();

        return back()->with('success', 'Social media links updated successfully.');
    }

    /**
     * Manage contact information
     */
    public function contact()
    {
        $contactInfo = GeneralSetting::byGroup('contact')->get()->keyBy('key');
        
        return view('admin.frontend.contact', compact('contactInfo'));
    }

    /**
     * Update contact information
     */
    public function updateContact(Request $request)
    {
        $validated = $request->validate([
            'contact' => 'required|array',
            'contact.email' => 'required|email',
            'contact.phone' => 'nullable|string',
            'contact.address' => 'nullable|string',
            'contact.address_my' => 'nullable|string',
            'contact.business_hours' => 'nullable|string',
            'contact.map_embed' => 'nullable|string',
        ]);

        foreach ($validated['contact'] as $key => $value) {
            GeneralSetting::updateOrCreate(
                ['key' => 'contact_' . $key],
                [
                    'value' => $value ?? '',
                    'group' => 'contact',
                    'type' => $key === 'email' ? 'email' : 'text',
                    'is_active' => true,
                ]
            );
        }

        $this->clearFrontendCache();

        return back()->with('success', 'Contact information updated successfully.');
    }

    /**
     * Manage announcements/banners
     */
    public function announcements()
    {
        $announcements = PageContent::forPage('announcements')
            ->active()
            ->orderBy('sort_order')
            ->get();
            
        return view('admin.frontend.announcements', compact('announcements'));
    }

    /**
     * Update announcements
     */
    public function updateAnnouncements(Request $request)
    {
        $validated = $request->validate([
            'announcements' => 'required|array',
            'announcements.*.key' => 'required|string',
            'announcements.*.content_en' => 'required|string',
            'announcements.*.content_my' => 'nullable|string',
            'announcements.*.is_active' => 'boolean',
            'announcements.*.sort_order' => 'integer',
        ]);

        foreach ($validated['announcements'] as $announcement) {
            PageContent::updateOrCreate(
                [
                    'page' => 'announcements',
                    'section' => 'banner',
                    'key' => $announcement['key'],
                ],
                [
                    'content_en' => $announcement['content_en'],
                    'content_my' => $announcement['content_my'] ?? null,
                    'type' => 'html',
                    'is_active' => $announcement['is_active'] ?? true,
                    'sort_order' => $announcement['sort_order'] ?? 0,
                ]
            );
        }

        $this->clearFrontendCache();

        return back()->with('success', 'Announcements updated successfully.');
    }

    /**
     * Upload image for frontend
     */
    public function uploadImage(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:5120', // 5MB max
            'key' => 'required|string',
            'group' => 'required|string|in:homepage,footer,theme,seo',
        ]);

        $file = $request->file('image');
        $path = $file->store('frontend/' . $validated['group'], 'public');

        // Save to settings
        GeneralSetting::updateOrCreate(
            ['key' => $validated['key']],
            [
                'value' => $path,
                'group' => $validated['group'],
                'type' => 'image',
                'is_active' => true,
            ]
        );

        $this->clearFrontendCache();

        return response()->json([
            'success' => true,
            'path' => Storage::url($path),
            'message' => 'Image uploaded successfully.',
        ]);
    }

    /**
     * Clear frontend cache
     */
    private function clearFrontendCache()
    {
        // Clear view cache
        
        // Clear application cache for settings
        
        // Clear route cache if needed
        
    }

    /**
     * Regenerate theme CSS
     */
    private function regenerateThemeCSS()
    {
        $themeSettings = GeneralSetting::byGroup('theme')->get()->keyBy('key');
        
        $css = ":root {
    --color-primary: {$themeSettings->get('primary_color', '#000000')->value};
    --color-secondary: {$themeSettings->get('secondary_color', '#ffffff')->value};
    --color-accent: {$themeSettings->get('accent_color', '#d4af37')->value};
    --font-family: {$themeSettings->get('font_family', 'inter')->value};
}";

        Storage::put('frontend/theme-variables.css', $css);
    }
}
