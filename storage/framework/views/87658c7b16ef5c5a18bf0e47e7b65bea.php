<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-base-url="<?php echo e(url('/')); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- Primary Meta Tags -->
    <title><?php echo $__env->yieldContent('title', 'Panchi Gallery - Discover Art Beyond Borders'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta-description', 'Premium art gallery featuring authentic Myanmar artworks. Buy, sell, and collect verified art with certificates of authenticity.'); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta-keywords', 'Art Gallery, Myanmar Art, Artist, Gallery in Myanmar, Yangon, Contemporary Art, Traditional Art'); ?>">
    <link rel="canonical" href="<?php echo $__env->yieldContent('canonical', url()->current()); ?>">
    
    <!-- Robots -->
    <?php
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
    ?>
    <?php if($isPrivate): ?>
        <meta name="robots" content="noindex, nofollow">
    <?php else: ?>
        <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <?php endif; ?>
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Panchi Gallery">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta property="og:title" content="<?php echo $__env->yieldContent('title', 'Panchi Gallery - Discover Art Beyond Borders'); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('meta-description', 'Premium art gallery featuring authentic Myanmar artworks. Buy, sell, and collect verified art with certificates of authenticity.'); ?>">
    <meta property="og:image" content="<?php echo $__env->yieldContent('meta-image', asset('images/og-default.jpg')); ?>">
    <meta property="og:locale" content="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo e(url()->current()); ?>">
    <meta property="twitter:title" content="<?php echo $__env->yieldContent('title', 'Panchi Gallery - Discover Art Beyond Borders'); ?>">
    <meta property="twitter:description" content="<?php echo $__env->yieldContent('meta-description', 'Premium art gallery featuring authentic Myanmar artworks. Buy, sell, and collect verified art with certificates of authenticity.'); ?>">
    <meta property="twitter:image" content="<?php echo $__env->yieldContent('meta-image', asset('images/og-default.jpg')); ?>">
    
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
    <?php
    $siteFavicon = general_setting_image('site_favicon', asset('favicon.png'));
?>
    <link rel="icon" type="image/png" href="<?php echo e($siteFavicon); ?>">
    
    <!-- Schema.org JSON-LD -->
    <?php echo $__env->yieldContent('schema'); ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Panchi Gallery",
        "url": "<?php echo e(url('/')); ?>",
        <?php
    $siteLogo = general_setting_image('site_logo', asset('images/logo.png'));
?>
        "logo": {
            "@type": "ImageObject",
            "url": "<?php echo e($siteLogo); ?>"
        },
        "description": "Premium art gallery featuring authentic Myanmar artworks. Buy, sell, and collect verified art with certificates of authenticity.",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "MM"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "customer service",
            "email": "contact@panchigallery.com",
            "availableLanguage": ["English", "Burmese"]
        },
        "sameAs": [
            "https://facebook.com/panchigallery",
            "https://instagram.com/panchigallery",
            "https://twitter.com/panchigallery"
        ]
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "Panchi Gallery",
        "url": "<?php echo e(url('/')); ?>",
        "description": "Premium art gallery featuring authentic Myanmar artworks. Buy, sell, and collect verified art with certificates of authenticity.",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?php echo e(url('/artworks')); ?>?search={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/css/loader.css', 'resources/js/app.js', 'resources/js/artwork-actions.js', 'resources/js/currency.js', 'resources/js/loader.js']); ?>
</head>
<body class="bg-white text-gray-900 antialiased" data-base-url="<?php echo e(url('/')); ?>">
    <!-- Professional Enterprise Loader -->
    <?php echo $__env->make('partials.loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Navigation -->
    <?php echo $__env->make('partials.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Main Content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    
    <!-- Footer -->
    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Mobile Menu (Hidden by default) -->
    <?php echo $__env->make('partials.mobile-menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>

    <!-- Global Scripts -->
    <script nonce="<?php echo e($cspNonce ?? ''); ?>">
        // Set base URL for API calls with fallback to data attribute
        window.baseUrl = '<?php echo e(url('/')); ?>' || document.body.getAttribute('data-base-url');

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
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
    
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\layouts\app.blade.php ENDPATH**/ ?>