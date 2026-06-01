<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;

class GeneralSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Text Settings
            ['key' => 'site_name', 'value' => 'Panchi Gallery', 'type' => 'text', 'group' => 'general', 'label' => 'Site Name'],
            ['key' => 'site_description', 'value' => 'Premier Myanmar Art Gallery', 'type' => 'text', 'group' => 'general', 'label' => 'Site Description'],
            ['key' => 'contact_email', 'value' => 'info@panchigallery.com', 'type' => 'text', 'group' => 'general', 'label' => 'Contact Email'],
            ['key' => 'contact_phone', 'value' => '+95 9 123 456 789', 'type' => 'text', 'group' => 'general', 'label' => 'Contact Phone'],
            ['key' => 'hero_title', 'value' => 'Discover Myanmar\'s Finest Art', 'type' => 'text', 'group' => 'home', 'label' => 'Hero Title'],
            ['key' => 'hero_subtitle', 'value' => 'Explore extraordinary artworks', 'type' => 'text', 'group' => 'home', 'label' => 'Hero Subtitle'],
            ['key' => 'platform_fee_percentage', 'value' => '10', 'type' => 'number', 'group' => 'payment', 'label' => 'Platform Fee %'],
            ['key' => 'enable_marketplace', 'value' => '1', 'type' => 'boolean', 'group' => 'features', 'label' => 'Enable Marketplace'],

            // Branding Images
            ['key' => 'site_logo', 'value' => null, 'type' => 'image', 'group' => 'branding', 'label' => 'Site Logo', 'description' => 'Main site logo. Recommended: 200x60px, PNG with transparency'],
            ['key' => 'site_logo_white', 'value' => null, 'type' => 'image', 'group' => 'branding', 'label' => 'Site Logo (White)', 'description' => 'White version of logo for dark backgrounds. Recommended: 200x60px, PNG'],
            ['key' => 'site_favicon', 'value' => null, 'type' => 'image', 'group' => 'branding', 'label' => 'Favicon', 'description' => 'Browser tab icon. Recommended: 32x32px or 64x64px, PNG/ICO'],
            ['key' => 'site_og_image', 'value' => null, 'type' => 'image', 'group' => 'branding', 'label' => 'Social Share Image', 'description' => 'Default image for social media sharing. Recommended: 1200x630px'],

            // Homepage Images
            ['key' => 'home_hero_background', 'value' => null, 'type' => 'image', 'group' => 'home', 'label' => 'Hero Background', 'description' => 'Main hero section background. Recommended: 1920x800px'],
            ['key' => 'home_featured_banner', 'value' => null, 'type' => 'image', 'group' => 'home', 'label' => 'Featured Banner', 'description' => 'Featured section banner. Recommended: 1200x400px'],
            ['key' => 'home_promo_banner_1', 'value' => null, 'type' => 'image', 'group' => 'home', 'label' => 'Promo Banner 1', 'description' => 'First promotional banner. Recommended: 600x400px'],
            ['key' => 'home_promo_banner_2', 'value' => null, 'type' => 'image', 'group' => 'home', 'label' => 'Promo Banner 2', 'description' => 'Second promotional banner. Recommended: 600x400px'],
            ['key' => 'home_newsletter_bg', 'value' => null, 'type' => 'image', 'group' => 'home', 'label' => 'Newsletter Background', 'description' => 'Newsletter section background. Recommended: 1920x400px'],

            // About Page Images
            ['key' => 'about_hero_background', 'value' => null, 'type' => 'image', 'group' => 'about', 'label' => 'About Hero Background', 'description' => 'About page hero background. Recommended: 1920x600px'],
            ['key' => 'about_section_image', 'value' => null, 'type' => 'image', 'group' => 'about', 'label' => 'About Section Image', 'description' => 'Main about section image. Recommended: 800x600px'],
            ['key' => 'about_mission_image', 'value' => null, 'type' => 'image', 'group' => 'about', 'label' => 'Mission Image', 'description' => 'Mission statement section image. Recommended: 600x400px'],
            ['key' => 'about_team_photo', 'value' => null, 'type' => 'image', 'group' => 'about', 'label' => 'Team Photo', 'description' => 'Team or founder photo. Recommended: 800x600px'],
            ['key' => 'about_gallery_image', 'value' => null, 'type' => 'image', 'group' => 'about', 'label' => 'Gallery Image', 'description' => 'Gallery space image. Recommended: 800x600px'],

            // Artists Page Images
            ['key' => 'artists_hero_background', 'value' => null, 'type' => 'image', 'group' => 'artists', 'label' => 'Artists Hero Background', 'description' => 'Artists listing page hero. Recommended: 1920x600px'],
            ['key' => 'artists_featured_artist', 'value' => null, 'type' => 'image', 'group' => 'artists', 'label' => 'Featured Artist Banner', 'description' => 'Featured artist section. Recommended: 800x500px'],

            // Artworks Page Images
            ['key' => 'artworks_hero_background', 'value' => null, 'type' => 'image', 'group' => 'artworks', 'label' => 'Artworks Hero Background', 'description' => 'Artworks gallery hero. Recommended: 1920x600px'],
            ['key' => 'artworks_category_banner', 'value' => null, 'type' => 'image', 'group' => 'artworks', 'label' => 'Category Banner', 'description' => 'Default category banner. Recommended: 1200x300px'],

            // Contact Page Images
            ['key' => 'contact_hero_background', 'value' => null, 'type' => 'image', 'group' => 'contact', 'label' => 'Contact Hero Background', 'description' => 'Contact page hero. Recommended: 1920x600px'],
            ['key' => 'contact_map_placeholder', 'value' => null, 'type' => 'image', 'group' => 'contact', 'label' => 'Map Placeholder', 'description' => 'Location map placeholder. Recommended: 800x400px'],

            // Authentication Page Images
            ['key' => 'auth_login_background', 'value' => null, 'type' => 'image', 'group' => 'auth', 'label' => 'Login Background', 'description' => 'Login page background. Recommended: 1920x1080px'],
            ['key' => 'auth_register_background', 'value' => null, 'type' => 'image', 'group' => 'auth', 'label' => 'Register Background', 'description' => 'Register page background. Recommended: 1920x1080px'],

            // Footer Images
            ['key' => 'footer_payment_icons', 'value' => null, 'type' => 'image', 'group' => 'footer', 'label' => 'Payment Icons', 'description' => 'Accepted payment methods icon strip. Recommended: 400x50px'],
            ['key' => 'footer_secure_badge', 'value' => null, 'type' => 'image', 'group' => 'footer', 'label' => 'Security Badge', 'description' => 'Security/trust badge. Recommended: 100x100px'],
        ];

        foreach ($settings as $index => $setting) {
            GeneralSetting::updateOrCreate(
                ['key' => $setting['key']],
                array_merge($setting, [
                    'is_active' => true,
                    'sort_order' => $index + 1
                ])
            );
        }

        $this->command->info('General settings seeded successfully!');
        $this->command->info('- ' . count(array_filter($settings, fn($s) => $s['type'] === 'text' || $s['type'] === 'number' || $s['type'] === 'boolean')) . ' text/number/boolean settings');
        $this->command->info('- ' . count(array_filter($settings, fn($s) => $s['type'] === 'image')) . ' image settings');
    }
}
