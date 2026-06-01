@extends('admin.layouts.app')

@section('title', 'Frontend Management - Panchi Gallery')

@section('header', 'Frontend Management')

@section('admin_content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Frontend Management</h1>
            <p class="text-gray-600 mt-1">Control 100% of your website's frontend content from this dashboard</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.cache.clear') }}" class="btn-elegant px-4 py-2 rounded-lg text-sm">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Clear Cache
            </a>
        </div>
    </div>

    <!-- Management Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Homepage Management -->
        <div class="card-luxury rounded-xl p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center text-white mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Homepage</h3>
                    <p class="text-sm text-gray-500">{{ count($homepageSections) }} sections</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">Manage hero, featured, about, artists, testimonials, and CTA sections</p>
            <a href="{{ route('admin.frontend.homepage') }}" class="btn-luxury w-full py-2 rounded-lg text-white text-sm font-medium block text-center">
                Manage Homepage
            </a>
        </div>

        <!-- Navigation Menu -->
        <div class="card-luxury rounded-xl p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center text-white mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Navigation</h3>
                    <p class="text-sm text-gray-500">Main menu</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">Control main navigation menu items, order, and visibility</p>
            <a href="{{ route('admin.frontend.navigation') }}" class="btn-luxury w-full py-2 rounded-lg text-white text-sm font-medium block text-center">
                Manage Navigation
            </a>
        </div>

        <!-- Footer Management -->
        <div class="card-luxury rounded-xl p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center text-white mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Footer</h3>
                    <p class="text-sm text-gray-500">{{ $footerSettings->count() }} settings</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">Manage footer columns, links, copyright, and bottom section</p>
            <a href="{{ route('admin.frontend.footer') }}" class="btn-luxury w-full py-2 rounded-lg text-white text-sm font-medium block text-center">
                Manage Footer
            </a>
        </div>

        <!-- Theme/Appearance -->
        <div class="card-luxury rounded-xl p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl flex items-center justify-center text-white mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Theme & Appearance</h3>
                    <p class="text-sm text-gray-500">{{ $themeSettings->count() }} settings</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">Customize colors, fonts, border radius, and visual elements</p>
            <a href="{{ route('admin.frontend.theme') }}" class="btn-luxury w-full py-2 rounded-lg text-white text-sm font-medium block text-center">
                Customize Theme
            </a>
        </div>

        <!-- SEO Settings -->
        <div class="card-luxury rounded-xl p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center text-white mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">SEO Settings</h3>
                    <p class="text-sm text-gray-500">{{ $seoSettings->count() }} settings</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">Meta tags, Open Graph, analytics, and search engine optimization</p>
            <a href="{{ route('admin.frontend.seo') }}" class="btn-luxury w-full py-2 rounded-lg text-white text-sm font-medium block text-center">
                Manage SEO
            </a>
        </div>

        <!-- Social Media -->
        <div class="card-luxury rounded-xl p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Social Media</h3>
                    <p class="text-sm text-gray-500">{{ $socialSettings->count() }} links</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">Manage social media links for Facebook, Instagram, Twitter, and more</p>
            <a href="{{ route('admin.frontend.social') }}" class="btn-luxury w-full py-2 rounded-lg text-white text-sm font-medium block text-center">
                Manage Social
            </a>
        </div>

        <!-- Contact Information -->
        <div class="card-luxury rounded-xl p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center text-white mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Contact Info</h3>
                    <p class="text-sm text-gray-500">Business details</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">Email, phone, address, business hours, and map embed</p>
            <a href="{{ route('admin.frontend.contact') }}" class="btn-luxury w-full py-2 rounded-lg text-white text-sm font-medium block text-center">
                Manage Contact
            </a>
        </div>

        <!-- Announcements -->
        <div class="card-luxury rounded-xl p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center text-white mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Announcements</h3>
                    <p class="text-sm text-gray-500">Banner messages</p>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-4">Create promotional banners, alerts, and announcement messages</p>
            <a href="{{ route('admin.frontend.announcements') }}" class="btn-luxury w-full py-2 rounded-lg text-white text-sm font-medium block text-center">
                Manage Announcements
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-gray-50 rounded-xl p-6">
        <h3 class="font-semibold text-lg mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.settings.general') }}" class="btn-elegant px-4 py-2 rounded-lg text-sm">
                General Settings
            </a>
            <a href="{{ route('admin.settings.images') }}" class="btn-elegant px-4 py-2 rounded-lg text-sm">
                Image Settings
            </a>
            <a href="{{ route('admin.page-contents.index') }}" class="btn-elegant px-4 py-2 rounded-lg text-sm">
                Page Contents
            </a>
            <a href="{{ route('admin.settings.language') }}" class="btn-elegant px-4 py-2 rounded-lg text-sm">
                Language Settings
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh cache status
    function updateCacheStatus() {
        fetch('{{ route("admin.settings.cache") }}')
            .then(response => response.text())
            .then(() => {
                console.log('Cache status checked');
            });
    }
</script>
@endpush

