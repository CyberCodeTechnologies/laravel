<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-base-url="{{ url('/') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Primary Meta Tags -->
    <title>@yield('title', 'Panchi Gallery - Discover Art Beyond Borders')</title>
    <meta name="description" content="@yield('meta-description', 'Premium art gallery featuring authentic Myanmar artworks. Buy, sell, and collect verified art with certificates of authenticity.')">
    <meta name="keywords" content="@yield('meta-keywords', 'Art Gallery, Myanmar Art, Artist, Gallery in Myanmar, Yangon, Contemporary Art, Traditional Art')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    
    <!-- Robots -->
    @php
        $privateRoutePatterns = [
            'login', 'register', 'password.*', 'verification.*',
            'artist.*', 'collector.*', 'dashboard.*', 'admin.*',
            'cart.*', 'checkout.*', 'wishlist.*', 'orders.*',
            'payment.*', 'profile.*', 'transactions.*', 'ownership.*',
            'resales.my-listings', 'resales.create', 'shipments.*',
            'custom-orders.*', 'commission.*'
        ];
        $isPrivate = false;
        foreach ($privateRoutePatterns as $pattern) {
            if (request()->routeIs($pattern)) {
                $isPrivate = true;
                break;
            }
        }
    @endphp
    @if($isPrivate)
        <meta name="robots" content="noindex, nofollow">
    @else
        <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    @endif
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Panchi Gallery">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Panchi Gallery - Discover Art Beyond Borders')">
    <meta property="og:description" content="@yield('meta-description', 'Premium art gallery featuring authentic Myanmar artworks. Buy, sell, and collect verified art with certificates of authenticity.')">
    <meta property="og:image" content="@yield('meta-image', asset('images/og-default.jpg'))">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Panchi Gallery - Discover Art Beyond Borders')">
    <meta property="twitter:description" content="@yield('meta-description', 'Premium art gallery featuring authentic Myanmar artworks. Buy, sell, and collect verified art with certificates of authenticity.')">
    <meta property="twitter:image" content="@yield('meta-image', asset('images/og-default.jpg'))">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&family=Noto+Sans+Myanmar:wght@300;400;500;600;700&family=Noto+Serif+Myanmar:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&family=Noto+Sans+Myanmar:wght@300;400;500;600;700&family=Noto+Serif+Myanmar:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    </noscript>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    @php
    $siteFavicon = general_setting_image('site_favicon', asset('favicon.png'));
@endphp
    <link rel="icon" type="image/png" href="{{ $siteFavicon }}">
    
    <!-- Schema.org JSON-LD -->
    @yield('schema')
    <x-schema-markup type="organization" />
    <x-schema-markup type="localBusiness" />
    @if(request()->route()->getName() && !str_starts_with(request()->route()->getName(), 'admin') && !str_starts_with(request()->route()->getName(), 'artist.') && !str_starts_with(request()->route()->getName(), 'collector.'))
        @php
            $breadcrumbs = collect([['name' => 'Home', 'url' => url('/')]]);
            if(request()->route()->getName() === 'artworks.index') {
                $breadcrumbs->push(['name' => 'Artworks', 'url' => route('artworks.index')]);
            } elseif(request()->route()->getName() === 'artists.index') {
                $breadcrumbs->push(['name' => 'Artists', 'url' => route('artists.index')]);
            } elseif(request()->route()->getName() === 'marketplace.index') {
                $breadcrumbs->push(['name' => 'Marketplace', 'url' => route('marketplace.index')]);
            } elseif(request()->route()->getName() === 'blog.index') {
                $breadcrumbs->push(['name' => 'Blog', 'url' => route('blog.index')]);
            }
        @endphp
        <x-schema-markup type="breadcrumb" :data="$breadcrumbs" />
    @endif
    
    @vite(['resources/css/app.css', 'resources/css/loader.css', 'resources/js/app.js', 'resources/js/artwork-actions.js', 'resources/js/currency.js', 'resources/js/loader.js'])
</head>
<body class="bg-white text-gray-900 antialiased" data-base-url="{{ url('/') }}">
    <!-- Professional Enterprise Loader -->
    @include('partials.loader')
    
    <!-- Navigation -->
    @include('partials.navigation')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('partials.footer')
    
    <!-- Mobile Menu (Hidden by default) -->
    @include('partials.mobile-menu')

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>

    <!-- Global Scripts -->
    <script nonce="{{ $cspNonce ?? '' }}">
        // Set base URL for API calls with fallback to data attribute
        window.baseUrl = '{{ url('/') }}' || document.body.getAttribute('data-base-url');

        /**
         * Display a toast notification
         * @param {string} message - The message to display
         * @param {string} type - The type of toast (success, error, warning, info)
         */
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            const bgColors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

            toast.className = `${bgColors[type] || bgColors.success} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full opacity-0`;
            toast.textContent = message;

            container.appendChild(toast);

            // Animate in
            requestAnimationFrame(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            });

            // Auto remove after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        // Initialize language and currency displays on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('App initialized');
        });

        // Global function to toggle mobile menu
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenu) {
                mobileMenu.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            }
        }
    </script>
    
    @stack('scripts')
    
    </body>
</html>
