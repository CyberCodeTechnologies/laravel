<!-- Desktop Navigation -->
<nav class="fixed top-0 left-0 right-0 bg-white z-50 shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    @php
                        $siteLogo = general_setting_image('site_logo', asset('images/logo.png'));
                        $siteName = general_setting('site_name', 'Panchi Gallery');
                    @endphp
                    <img src="{{ $siteLogo }}"
                         alt="{{ $siteName }}"
                         class="h-14 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-4">
                @if(cms_is_active('nav_home'))
                    <a href="{{ route('home') }}" class="link-elegant font-medium px-2 {{ request()->routeIs('home') ? 'text-black' : 'text-gray-600 hover:text-black' }}">
                        {{ cms_content('nav_home', __('messages.home')) }}
                    </a>
                @endif
                
                <a href="{{ route('public.artworks.index') }}" class="link-elegant font-medium px-2 {{ request()->routeIs('public.artworks.index') ? 'text-black' : 'text-gray-600 hover:text-black' }}">
                    {{ cms_content('nav_artworks', __('messages.artworks')) }}
                </a>
                
                <a href="{{ route('public.artists.index') }}" class="link-elegant font-medium px-2 {{ request()->routeIs('public.artists.index') ? 'text-black' : 'text-gray-600 hover:text-black' }}">
                    {{ cms_content('nav_artists', __('messages.artists')) }}
                </a>
                
                @if(cms_is_active('nav_exhibitions'))
                    <a href="{{ route('public.exhibitions.index') }}" class="link-elegant font-medium px-2 {{ request()->routeIs('public.exhibitions.*') ? 'text-black' : 'text-gray-600 hover:text-black' }}">
                        {{ cms_content('nav_exhibitions', __('messages.exhibitions')) }}
                    </a>
                @endif
                
                @if(cms_is_active('nav_blog'))
                    <a href="{{ route('blog.index') }}" class="link-elegant font-medium px-2 {{ request()->routeIs('blog.index') ? 'text-black' : 'text-gray-600 hover:text-black' }}">
                        {{ cms_content('nav_blog', __('messages.blog')) }}
                    </a>
                @endif
                
                @if(cms_is_active('nav_marketplace'))
                    <a href="{{ route('marketplace.index') }}" class="link-elegant font-medium px-2 {{ request()->routeIs('marketplace.index') ? 'text-black' : 'text-gray-600 hover:text-black' }}">
                        {{ cms_content('nav_marketplace', __('messages.marketplace')) }}
                    </a>
                @endif
                
                @if(cms_is_active('nav_orders'))
                    <a href="{{ route('custom-orders.create') }}" class="link-elegant font-medium px-2 {{ request()->routeIs('custom-orders.*') ? 'text-black' : 'text-gray-600 hover:text-black' }}">
                        {{ cms_content('nav_orders', __('messages.orders')) }}
                    </a>
                @endif
            </div>

            <!-- Right Side Actions -->
            <div class="hidden md:flex items-center space-x-6">
                <!-- Language Switcher -->
                <div class="relative group px-1">
                    <!-- Fallback form for language switching -->
                    <form id="language-form" method="POST" action="{{ route('language.switch') }}" style="display: none;">
                        @csrf
                        <input type="hidden" name="language" id="language-input">
                    </form>
                    
                    <button class="flex items-center space-x-1 text-gray-600 hover:text-black transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                        </svg>
                        <span class="text-xs">{{ app()->getLocale() === 'en' ? 'EN' : 'MY' }}</span>
                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute right-0 mt-0 w-28 bg-white border border-gray-200 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50">
                        @if(app()->getLocale() === 'en')
                            <button onclick="switchLanguageWithFallback('my')" class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl transition-colors">
                                {{ __('messages.myanmar') }}
                            </button>
                        @else
                            <button onclick="switchLanguageWithFallback('en')" class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl transition-colors">
                                {{ __('messages.english') }}
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Divider -->
                <div class="w-px h-6 bg-gray-300"></div>

                <!-- Wishlist -->
                <a href="{{ route('wishlist.index') }}" class="relative p-2 text-gray-600 hover:text-black transition-colors group">
                    <svg class="w-6 h-6 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    @if(auth()->check())
                        @php $wishlistCount = optional(auth()->user()->wishlistItems)->count() ?? 0; @endphp
                        <span id="wishlist-badge" class="absolute -top-1 -right-1 w-5 h-5 bg-black text-white text-xs rounded-full flex items-center justify-center font-medium {{ $wishlistCount == 0 ? 'hidden' : '' }}">
                            {{ $wishlistCount }}
                        </span>
                    @endif
                </a>

                <!-- Shopping Cart -->
                <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-black transition-colors group">
                    <svg class="w-6 h-6 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span id="cart-badge" class="absolute -top-1 -right-1 w-5 h-5 bg-black text-white text-xs rounded-full flex items-center justify-center font-medium {{ $cartCount > 0 ? '' : 'hidden' }}">
                        {{ $cartCount }}
                    </span>
                </a>

                <!-- User Menu -->
                @guest
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-black font-medium transition-colors">
                            {{ __('messages.login') }}
                        </a>
                        <a href="{{ route('register') }}" class="text-black hover:text-gray-600 font-medium transition-colors">
                            {{ __('messages.sign_up') }}
                        </a>
                    </div>
                @else
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-gray-600 hover:text-black transition-colors">
                            <div class="w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center ring-2 ring-transparent group-hover:ring-gray-300 transition-all duration-300">
                                <span class="text-sm font-semibold text-gray-700">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            </div>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-0 w-56 bg-white border border-gray-200 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ optional(auth()->user())->name ?? '' }}</p>
                                <p class="text-xs text-gray-500">{{ optional(auth()->user())->email ?? '' }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                {{ __('messages.user_menu.dashboard') }}
                            </a>
                            <a href="{{ route('profile') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ __('messages.user_menu.profile') }}
                            </a>
                            <a href="{{ route('wishlist.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                {{ __('messages.user_menu.wishlist') }}
                            </a>
                            @if(optional(auth()->user())->isArtist())
                                <a href="{{ route('artist.dashboard') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ __('messages.artist_dashboard.title') }}
                                </a>
                            @endif
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-b-xl transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    {{ __('messages.user_menu.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button onclick="toggleMobileMenu()" class="p-2 text-gray-600 hover:text-black transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Spacer for fixed navigation -->
<div class="h-20"></div>
