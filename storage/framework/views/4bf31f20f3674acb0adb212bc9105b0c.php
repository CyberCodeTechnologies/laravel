<!-- Mobile Menu -->
<div id="mobile-menu" class="fixed inset-0 bg-white z-50 hidden">
    <div class="flex flex-col h-full">
        <!-- Mobile Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <?php
    $siteLogo = general_setting_image('site_logo', asset('images/logo.png'));
    $siteName = general_setting('site_name', 'Panchi Gallery');
?>
                <img src="<?php echo e($siteLogo); ?>" 
                     alt="<?php echo e($siteName); ?>" 
                     class="h-14 w-auto object-contain">
                <span class="font-serif text-xl font-bold text-black"><?php echo e($siteName); ?></span>
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
                <?php if(cms_is_active('nav_home')): ?>
                    <a href="<?php echo e(route('home')); ?>" class="block py-3 text-lg font-medium <?php echo e(request()->routeIs('home') ? 'text-black' : 'text-gray-600'); ?> hover:text-black transition-colors" onclick="toggleMobileMenu()">
                        <?php echo e(cms_content('nav_home', __('messages.home'))); ?>

                    </a>
                <?php endif; ?>

                <a href="<?php echo e(route('public.artworks.index')); ?>" class="block py-3 text-lg font-medium <?php echo e(request()->routeIs('public.artworks.index') ? 'text-black' : 'text-gray-600'); ?> hover:text-black transition-colors" onclick="toggleMobileMenu()">
                    <?php echo e(cms_content('nav_artworks', __('messages.artworks'))); ?>

                </a>

                <a href="<?php echo e(route('public.artists.index')); ?>" class="block py-3 text-lg font-medium <?php echo e(request()->routeIs('public.artists.index') ? 'text-black' : 'text-gray-600'); ?> hover:text-black transition-colors" onclick="toggleMobileMenu()">
                    <?php echo e(cms_content('nav_artists', __('messages.artists'))); ?>

                </a>

                <?php if(cms_is_active('nav_exhibitions')): ?>
                    <a href="<?php echo e(route('public.exhibitions.index')); ?>" class="block py-3 text-lg font-medium <?php echo e(request()->routeIs('public.exhibitions.*') ? 'text-black' : 'text-gray-600'); ?> hover:text-black transition-colors" onclick="toggleMobileMenu()">
                        <?php echo e(cms_content('nav_exhibitions', __('messages.exhibitions'))); ?>

                    </a>
                <?php endif; ?>

                <?php if(cms_is_active('nav_blog')): ?>
                    <a href="<?php echo e(route('blog.index')); ?>" class="block py-3 text-lg font-medium <?php echo e(request()->routeIs('blog.index') ? 'text-black' : 'text-gray-600'); ?> hover:text-black transition-colors" onclick="toggleMobileMenu()">
                        <?php echo e(cms_content('nav_blog', __('messages.blog'))); ?>

                    </a>
                <?php endif; ?>

                <?php if(cms_is_active('nav_marketplace')): ?>
                    <a href="<?php echo e(route('marketplace.index')); ?>" class="block py-3 text-lg font-medium <?php echo e(request()->routeIs('marketplace.index') ? 'text-black' : 'text-gray-600'); ?> hover:text-black transition-colors" onclick="toggleMobileMenu()">
                        <?php echo e(cms_content('nav_marketplace', __('messages.marketplace'))); ?>

                    </a>
                <?php endif; ?>

                <?php if(cms_is_active('nav_orders')): ?>
                    <a href="<?php echo e(route('custom-orders.create')); ?>" class="block py-3 text-lg font-medium <?php echo e(request()->routeIs('custom-orders.*') ? 'text-black' : 'text-gray-600'); ?> hover:text-black transition-colors" onclick="toggleMobileMenu()">
                        <?php echo e(cms_content('nav_orders', __('messages.orders'))); ?>

                    </a>
                <?php endif; ?>

                <?php if(cms_is_active('nav_about')): ?>
                    <a href="<?php echo e(route('about')); ?>" class="block py-3 text-lg font-medium <?php echo e(request()->routeIs('about') ? 'text-black' : 'text-gray-600'); ?> hover:text-black transition-colors" onclick="toggleMobileMenu()">
                        <?php echo e(cms_content('nav_about', __('messages.about'))); ?>

                    </a>
                <?php endif; ?>

                <a href="<?php echo e(route('cart.index')); ?>" class="block py-3 text-lg font-medium <?php echo e(request()->routeIs('cart.*') ? 'text-black' : 'text-gray-600'); ?> hover:text-black transition-colors" onclick="toggleMobileMenu()">
                    <?php echo e(__('messages.cart')); ?><?php echo $cartCount > 0 ? '<span class="ml-2 bg-black text-white text-xs px-2 py-1 rounded-full">' . $cartCount . '</span>' : ''; ?>

                </a>
            </div>
            
            <!-- Language & Currency -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.language')); ?></label>
                        <div class="flex space-x-2">
                            <button onclick="switchLanguage('en'); toggleMobileMenu();" class="flex-1 py-2 px-4 <?php echo e(app()->getLocale() === 'en' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> rounded-lg font-medium transition-colors">
                                <?php echo e(__('messages.english')); ?>

                            </button>
                            <button onclick="switchLanguage('my'); toggleMobileMenu();" class="flex-1 py-2 px-4 <?php echo e(app()->getLocale() === 'my' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> rounded-lg font-medium transition-colors">
                                <?php echo e(__('messages.myanmar')); ?>

                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('messages.currency')); ?></label>
                        <div class="flex space-x-2">
                            <button onclick="switchCurrency('USD'); toggleMobileMenu();" class="flex-1 py-2 px-4 <?php echo e(($currentCurrency ?? 'USD') === 'USD' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> rounded-lg font-medium transition-colors">
                                USD
                            </button>
                            <button onclick="switchCurrency('MMK'); toggleMobileMenu();" class="flex-1 py-2 px-4 <?php echo e(($currentCurrency ?? 'USD') === 'MMK' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> rounded-lg font-medium transition-colors">
                                MMK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Actions -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <?php if(auth()->guard()->guest()): ?>
                    <div class="space-y-3">
                        <a href="<?php echo e(route('login')); ?>" class="block w-full py-3 px-4 bg-gray-100 text-gray-700 rounded-lg font-medium text-center hover:bg-gray-200 transition-colors" onclick="toggleMobileMenu()">
                            <?php echo e(__('messages.login')); ?>

                        </a>
                        <a href="<?php echo e(route('register')); ?>" class="block w-full py-3 px-4 bg-black text-white rounded-lg font-medium text-center hover:bg-gray-800 transition-colors" onclick="toggleMobileMenu()">
                            <?php echo e(__('messages.sign_up')); ?>

                        </a>
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-lg font-medium text-gray-700">
                                    <?php echo e(strtoupper(substr(optional(auth()->user())->name ?? '', 0, 1))); ?>

                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900"><?php echo e(optional(auth()->user())->name ?? ''); ?></p>
                                <p class="text-sm text-gray-500"><?php echo e(optional(auth()->user())->email ?? ''); ?></p>
                            </div>
                        </div>
                        
                        <a href="<?php echo e(route('dashboard')); ?>" class="block py-3 text-lg font-medium <?php echo e(request()->routeIs('dashboard') ? 'text-black' : 'text-gray-600'); ?> hover:text-black transition-colors" onclick="toggleMobileMenu()">
                            <?php echo e(__('messages.user_menu.dashboard')); ?>

                        </a>
                        <a href="<?php echo e(route('profile')); ?>" class="block py-3 text-lg font-medium <?php echo e(request()->routeIs('profile') ? 'text-black' : 'text-gray-600'); ?> hover:text-black transition-colors" onclick="toggleMobileMenu()">
                            <?php echo e(__('messages.user_menu.profile')); ?>

                        </a>
                        <a href="<?php echo e(route('wishlist.index')); ?>" class="block py-3 text-lg font-medium <?php echo e(request()->routeIs('wishlist.*') ? 'text-black' : 'text-gray-600'); ?> hover:text-black transition-colors" onclick="toggleMobileMenu()">
                            <?php echo e(__('messages.user_menu.wishlist')); ?>

                        </a>
                        <?php if(optional(auth()->user())->isArtist()): ?>
                            <a href="<?php echo e(route('artist.dashboard')); ?>" class="block py-3 text-lg font-medium <?php echo e(request()->routeIs('artist.dashboard') ? 'text-black' : 'text-gray-600'); ?> hover:text-black transition-colors" onclick="toggleMobileMenu()">
                                <?php echo e(__('messages.artist_dashboard.title')); ?>

                            </a>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="block w-full py-3 px-4 bg-gray-100 text-gray-700 rounded-lg font-medium text-center hover:bg-gray-200 transition-colors" onclick="toggleMobileMenu()">
                                <?php echo e(__('messages.user_menu.logout')); ?>

                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views/partials/mobile-menu.blade.php ENDPATH**/ ?>