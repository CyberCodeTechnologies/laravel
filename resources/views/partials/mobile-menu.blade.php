<!-- Mobile Menu -->
<div id="mobile-menu" class="fixed inset-0 bg-white z-50 hidden">
    <div class="flex flex-col h-full">
        <!-- Mobile Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                @php
    $siteLogo = general_setting_image('site_logo', asset('images/logo.png'));
    $siteName = general_setting('site_name', 'Panchi Gallery');
@endphp
                <img src="{{ $siteLogo }}" 
                     alt="{{ $siteName }}" 
                     class="h-14 w-auto object-contain">
                <span class="font-serif text-xl font-bold text-black">{{ $siteName }}</span>
            </div>
            <button onclick="toggleMobileMenu()" class="p-2 text-gray-600 hover:text-black transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Mobile Navigation -->
        <nav class="flex-1 overflow-y-auto p-4">
            <div class="space-y-4">
                @if(cms_is_active('nav_home'))
                    <a href="{{ route('home') }}" class="block py-3 text-lg font-medium {{ request()->routeIs('home') ? 'text-black' : 'text-gray-600' }} hover:text-black transition-colors" onclick="toggleMobileMenu()">
                        {{ cms_content('nav_home', __('messages.home')) }}
                    </a>
                @endif

                <a href="{{ route('public.artworks.index') }}" class="block py-3 text-lg font-medium {{ request()->routeIs('public.artworks.index') ? 'text-black' : 'text-gray-600' }} hover:text-black transition-colors" onclick="toggleMobileMenu()">
                    {{ cms_content('nav_artworks', __('messages.artworks')) }}
                </a>

                <a href="{{ route('public.artists.index') }}" class="block py-3 text-lg font-medium {{ request()->routeIs('public.artists.index') ? 'text-black' : 'text-gray-600' }} hover:text-black transition-colors" onclick="toggleMobileMenu()">
                    {{ cms_content('nav_artists', __('messages.artists')) }}
                </a>

                @if(cms_is_active('nav_exhibitions'))
                    <a href="{{ route('public.exhibitions.index') }}" class="block py-3 text-lg font-medium {{ request()->routeIs('public.exhibitions.*') ? 'text-black' : 'text-gray-600' }} hover:text-black transition-colors" onclick="toggleMobileMenu()">
                        {{ cms_content('nav_exhibitions', __('messages.exhibitions')) }}
                    </a>
                @endif

                @if(cms_is_active('nav_blog'))
                    <a href="{{ route('blog.index') }}" class="block py-3 text-lg font-medium {{ request()->routeIs('blog.index') ? 'text-black' : 'text-gray-600' }} hover:text-black transition-colors" onclick="toggleMobileMenu()">
                        {{ cms_content('nav_blog', __('messages.blog')) }}
                    </a>
                @endif

                @if(cms_is_active('nav_marketplace'))
                    <a href="{{ route('marketplace.index') }}" class="block py-3 text-lg font-medium {{ request()->routeIs('marketplace.index') ? 'text-black' : 'text-gray-600' }} hover:text-black transition-colors" onclick="toggleMobileMenu()">
                        {{ cms_content('nav_marketplace', __('messages.marketplace')) }}
                    </a>
                @endif

                @if(cms_is_active('nav_orders'))
                    <a href="{{ route('custom-orders.create') }}" class="block py-3 text-lg font-medium {{ request()->routeIs('custom-orders.*') ? 'text-black' : 'text-gray-600' }} hover:text-black transition-colors" onclick="toggleMobileMenu()">
                        {{ cms_content('nav_orders', __('messages.orders')) }}
                    </a>
                @endif

                @if(cms_is_active('nav_about'))
                    <a href="{{ route('about') }}" class="block py-3 text-lg font-medium {{ request()->routeIs('about') ? 'text-black' : 'text-gray-600' }} hover:text-black transition-colors" onclick="toggleMobileMenu()">
                        {{ cms_content('nav_about', __('messages.about')) }}
                    </a>
                @endif

                <a href="{{ route('cart.index') }}" class="block py-3 text-lg font-medium {{ request()->routeIs('cart.*') ? 'text-black' : 'text-gray-600' }} hover:text-black transition-colors" onclick="toggleMobileMenu()">
                    {{ __('messages.cart') }}{!! $cartCount > 0 ? '<span class="ml-2 bg-black text-white text-xs px-2 py-1 rounded-full">' . $cartCount . '</span>' : '' !!}
                </a>
            </div>
            
            <!-- Language & Currency -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.language') }}</label>
                        <div class="flex space-x-2">
                            <button onclick="switchLanguage('en'); toggleMobileMenu();" class="flex-1 py-2 px-4 {{ app()->getLocale() === 'en' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg font-medium transition-colors">
                                {{ __('messages.english') }}
                            </button>
                            <button onclick="switchLanguage('my'); toggleMobileMenu();" class="flex-1 py-2 px-4 {{ app()->getLocale() === 'my' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg font-medium transition-colors">
                                {{ __('messages.myanmar') }}
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.currency') }}</label>
                        <div class="flex space-x-2">
                            <button onclick="switchCurrency('USD'); toggleMobileMenu();" class="flex-1 py-2 px-4 {{ ($currentCurrency ?? 'USD') === 'USD' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg font-medium transition-colors">
                                USD
                            </button>
                            <button onclick="switchCurrency('MMK'); toggleMobileMenu();" class="flex-1 py-2 px-4 {{ ($currentCurrency ?? 'USD') === 'MMK' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg font-medium transition-colors">
                                MMK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Actions -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                @guest
                    <div class="space-y-3">
                        <a href="{{ route('login') }}" class="block w-full py-3 px-4 bg-gray-100 text-gray-700 rounded-lg font-medium text-center hover:bg-gray-200 transition-colors" onclick="toggleMobileMenu()">
                            {{ __('messages.login') }}
                        </a>
                        <a href="{{ route('register') }}" class="block w-full py-3 px-4 bg-black text-white rounded-lg font-medium text-center hover:bg-gray-800 transition-colors" onclick="toggleMobileMenu()">
                            {{ __('messages.sign_up') }}
                        </a>
                    </div>
                @else
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-lg font-medium text-gray-700">
                                    {{ strtoupper(substr(optional(auth()->user())->name ?? '', 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ optional(auth()->user())->name ?? '' }}</p>
                                <p class="text-sm text-gray-500">{{ optional(auth()->user())->email ?? '' }}</p>
                            </div>
                        </div>
                        
                        <a href="{{ route('dashboard') }}" class="block py-3 text-lg font-medium {{ request()->routeIs('dashboard') ? 'text-black' : 'text-gray-600' }} hover:text-black transition-colors" onclick="toggleMobileMenu()">
                            {{ __('messages.user_menu.dashboard') }}
                        </a>
                        <a href="{{ route('profile') }}" class="block py-3 text-lg font-medium {{ request()->routeIs('profile') ? 'text-black' : 'text-gray-600' }} hover:text-black transition-colors" onclick="toggleMobileMenu()">
                            {{ __('messages.user_menu.profile') }}
                        </a>
                        <a href="{{ route('wishlist.index') }}" class="block py-3 text-lg font-medium {{ request()->routeIs('wishlist.*') ? 'text-black' : 'text-gray-600' }} hover:text-black transition-colors" onclick="toggleMobileMenu()">
                            {{ __('messages.user_menu.wishlist') }}
                        </a>
                        @if(optional(auth()->user())->isArtist())
                            <a href="{{ route('artist.dashboard') }}" class="block py-3 text-lg font-medium {{ request()->routeIs('artist.dashboard') ? 'text-black' : 'text-gray-600' }} hover:text-black transition-colors" onclick="toggleMobileMenu()">
                                {{ __('messages.artist_dashboard.title') }}
                            </a>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full py-3 px-4 bg-gray-100 text-gray-700 rounded-lg font-medium text-center hover:bg-gray-200 transition-colors" onclick="toggleMobileMenu()">
                                {{ __('messages.user_menu.logout') }}
                            </button>
                        </form>
                    </div>
                @endguest
            </div>
        </nav>
    </div>
</div>
