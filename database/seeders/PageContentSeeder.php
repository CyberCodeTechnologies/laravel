<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Home page content
        $homeContent = [
            [
                'page' => 'home',
                'section' => 'hero',
                'key' => 'home_title',
                'content_en' => 'Panchi Gallery - Buy Authentic Myanmar Art Online',
                'content_my' => 'ပန်ချီ ဂါလာရီ - မြန်မာ အနုပညာ များကို အစစ်အမှန် ဝယ်ယူပါ',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'page' => 'home',
                'section' => 'hero',
                'key' => 'home_meta_description',
                'content_en' => 'Discover and buy authentic Myanmar artworks from verified artists. Limited edition pieces, exclusive collections, and certificates of authenticity. Shop now!',
                'content_my' => 'အစစ်အမှန် ဧည့်ကြိုဆိုထားသော မြန်မာ အနုပညာ များကို ရှာဖွေပြီး ဝယ်ယူပါ။ ကန့်သတ်ထားသော ထုတ်ကုန်များ၊ သီးသန့် စုဆောင်းမှုများနှင့် အစစ်အမှန် �လက်မှတ်များဖြင့် ယခုပင် ဝယ်ယူပါ!',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'page' => 'home',
                'section' => 'hero',
                'key' => 'hero_premium_marketplace',
                'content_en' => 'Premium Art Marketplace',
                'content_my' => 'အကောင်းဆုံး အနုပညာ ဈေးကွက်',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'page' => 'home',
                'section' => 'hero',
                'key' => 'hero_title',
                'content_en' => 'Own Art That Speaks to You',
                'content_my' => 'သင့်အတွက် ပြောဆိုနိုင်သော အနုပညာကို ပိုင်ဆိုင်ပါ',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'page' => 'home',
                'section' => 'hero',
                'key' => 'hero_description',
                'content_en' => 'Discover authentic Myanmar masterpieces from verified artists. Every piece comes with a certificate of authenticity and secure ownership tracking. Start your collection today!',
                'content_my' => 'အစစ်အမှန် ဧည့်ကြိုဆိုထားသော မြန်မာ အနုပညာ များကို ရှာဖွေပါ။ အစုံတိုင်းတွင် အစစ်အမှန် လက်မှတ်နှင့် လုံခြုံသော ပိုင်ဆိုင်မှု ခြေရာခံများ ပါဝင်ပါသည်။ ယခုပင် သင့် စုဆောင်းမှုကို စတင်ပါ!',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'page' => 'home',
                'section' => 'hero',
                'key' => 'shop_now',
                'content_en' => 'Shop Now',
                'content_my' => 'ယခုဝယ်ယူပါ',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'page' => 'home',
                'section' => 'hero',
                'key' => 'flash_sale',
                'content_en' => 'Flash Sale',
                'content_my' => 'အမြန်ရောင်းပွဲ',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 7,
            ],
        ];

        // About page content
        $aboutContent = [
            [
                'page' => 'about',
                'section' => 'hero',
                'key' => 'about_title',
                'content_en' => 'About Panchi Gallery',
                'content_my' => 'ပန်ချီ ဂါလာရီ အကြောင်း',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'page' => 'about',
                'section' => 'hero',
                'key' => 'about_hero_title',
                'content_en' => 'Bridging Myanmar Art with the World',
                'content_my' => 'မြန်မာ အနုပညာနှင့် ကမ္ဘာကြီးကို ဆက်သွယ်ခြင်း',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'page' => 'about',
                'section' => 'hero',
                'key' => 'about_hero_subtitle_1',
                'content_en' => 'Where tradition meets contemporary expression',
                'content_my' => 'ရိုးရာဓလေ့နှင့် ခေတ်သစ် ဖော်ပြချက်များ တွေ့ဆုံရာ',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'page' => 'about',
                'section' => 'hero',
                'key' => 'about_hero_subtitle_2',
                'content_en' => 'Empowering Myanmar artists to reach global collectors',
                'content_my' => 'မြန်မာ အနုပညာရှင်များကို ကမ္ဘာ့အဆင့် စုဆောင်းသူများထံ ရောက်ရှိစေရန် အားပေးခြင်း',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        // Contact page content
        $contactContent = [
            [
                'page' => 'contact',
                'section' => 'hero',
                'key' => 'contact_title',
                'content_en' => 'Contact Us',
                'content_my' => 'ကျွန်ုပ်တို့ကို ဆက်သွယ်ပါ',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'page' => 'contact',
                'section' => 'hero',
                'key' => 'get_in_touch',
                'content_en' => 'Get in Touch',
                'content_my' => 'ဆက်သွယ်ပါ',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'page' => 'contact',
                'section' => 'hero',
                'key' => 'were_here_to_help',
                'content_en' => 'We\'re here to help',
                'content_my' => 'ကျွန်ုပ်တို့သည် ကူညီရန် ရှိပါသည်',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        // FAQ page content
        $faqContent = [
            [
                'page' => 'faq',
                'section' => 'hero',
                'key' => 'faq_title',
                'content_en' => 'Frequently Asked Questions',
                'content_my' => 'မကြာခဏ မေးလေ့ရှိသော မေးခွန်းများ',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'page' => 'faq',
                'section' => 'hero',
                'key' => 'faq_subtitle',
                'content_en' => 'Find answers to common questions about Panchi Gallery',
                'content_my' => 'ပန်ချီ ဂါလာရီ အကြောင်း မကြာခဏ မေးလေ့ရှိသော မေးခွန်းများကို ရှာဖွေပါ',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        // Navigation content
        $navContent = [
            [
                'page' => 'navigation',
                'section' => 'main',
                'key' => 'nav_home',
                'content_en' => 'Home',
                'content_my' => 'ပင်မစာမျက်နှာ',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'page' => 'navigation',
                'section' => 'main',
                'key' => 'nav_artworks',
                'content_en' => 'Artworks',
                'content_my' => 'အနုပညာ လက်ရာများ',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'page' => 'navigation',
                'section' => 'main',
                'key' => 'nav_artists',
                'content_en' => 'Artists',
                'content_my' => 'အနုပညာရှင်များ',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'page' => 'navigation',
                'section' => 'main',
                'key' => 'nav_about',
                'content_en' => 'About',
                'content_my' => 'အကြောင်း',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'page' => 'navigation',
                'section' => 'main',
                'key' => 'nav_contact',
                'content_en' => 'Contact',
                'content_my' => 'ဆက်သွယ်ရန်',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        // Insert all content
        foreach (array_merge($homeContent, $aboutContent, $contactContent, $faqContent, $navContent) as $content) {
            PageContent::updateOrCreate(
                ['key' => $content['key']],
                $content
            );
        }

        $this->command->info('Page content seeded successfully.');
    }
}
