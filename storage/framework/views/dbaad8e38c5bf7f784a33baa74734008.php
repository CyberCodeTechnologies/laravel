<footer class="bg-gray-900/95 text-white border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            <!-- Brand -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center space-x-3 mb-6">
                    <?php
    $siteLogo = general_setting_image('site_logo', asset('images/white-logo.png'));
    $siteName = general_setting('site_name', 'Panchi Gallery');
?>
                    <img src="<?php echo e($siteLogo); ?>"
                         alt="<?php echo e($siteName); ?>"
                         class="h-12 w-auto object-contain">
                </div>
                <p class="text-gray-300 mb-6 max-w-md leading-relaxed">
                    <?php echo e(__('messages.footer_description')); ?>

                </p>
                <div class="flex items-center gap-3">
                    <span class="text-gray-400 text-sm"><?php echo e(__('messages.follow_us')); ?></span>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-blue-600 rounded-full flex items-center justify-center text-gray-300 hover:text-white transition-all duration-300 group shadow-lg hover:shadow-blue-500/30">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-sky-500 rounded-full flex items-center justify-center text-gray-300 hover:text-white transition-all duration-300 group shadow-lg hover:shadow-sky-500/30">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-pink-600 rounded-full flex items-center justify-center text-gray-300 hover:text-white transition-all duration-300 group shadow-lg hover:shadow-pink-500/30">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.405a1.44 1.44 0 112.881.001 1.44 1.44 0 01-2.881-.001z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-red-600 rounded-full flex items-center justify-center text-gray-300 hover:text-white transition-all duration-300 group shadow-lg hover:shadow-red-500/30">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 class="font-serif text-lg font-semibold mb-6 text-white"><?php echo e(__('messages.explore')); ?></h3>
                <ul class="space-y-3">
                    <li>
                        <a href="<?php echo e(route('public.artworks.index')); ?>" class="text-white hover:text-gray-300 transition-colors">
                            <?php echo e(__('messages.all_artworks')); ?>

                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('public.artists.index')); ?>" class="text-white hover:text-gray-300 transition-colors">
                            <?php echo e(__('messages.artists')); ?>

                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('public.exhibitions.index')); ?>" class="text-white hover:text-gray-300 transition-colors">
                            <?php echo e(__('messages.exhibitions')); ?>

                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('blog.index')); ?>" class="text-white hover:text-gray-300 transition-colors">
                            <?php echo e(__('messages.blog')); ?>

                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('marketplace.index')); ?>" class="text-white hover:text-gray-300 transition-colors">
                            <?php echo e(__('messages.marketplace')); ?>

                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('custom-orders.create')); ?>" class="text-white hover:text-gray-300 transition-colors">
                            <?php echo e(__('messages.custom_orders')); ?>

                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('collections')); ?>" class="text-white hover:text-gray-300 transition-colors">
                            <?php echo e(__('messages.collections')); ?>

                        </a>
                    </li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h3 class="font-serif text-lg font-semibold mb-6 text-white"><?php echo e(__('messages.support')); ?></h3>
                <ul class="space-y-3">
                    <li>
                        <a href="<?php echo e(route('about')); ?>" class="text-white hover:text-gray-300 transition-colors">
                            <?php echo e(__('messages.about_us')); ?>

                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('contact')); ?>" class="text-white hover:text-gray-300 transition-colors">
                            <?php echo e(__('messages.contact')); ?>

                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('faq')); ?>" class="text-white hover:text-gray-300 transition-colors">
                            <?php echo e(__('messages.faq')); ?>

                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('privacy')); ?>" class="text-white hover:text-gray-300 transition-colors">
                            <?php echo e(__('messages.privacy_policy')); ?>

                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('terms')); ?>" class="text-white hover:text-gray-300 transition-colors">
                            <?php echo e(__('messages.terms_of_service')); ?>

                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Newsletter -->
        <div class="mt-16 pt-8 border-t border-gray-800">
            <div class="max-w-2xl mx-auto text-center">
                <h3 class="font-serif text-xl font-semibold mb-4 text-white"><?php echo e(__('messages.stay_updated')); ?></h3>
                <p class="text-gray-300 mb-6 leading-relaxed">
                    <?php echo e(__('messages.get_exclusive_access')); ?>

                </p>
                <form action="<?php echo e(route('newsletter.subscribe')); ?>" method="POST" class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                    <?php echo csrf_field(); ?>
                    <input
                        type="email"
                        name="email"
                        placeholder="<?php echo e(__('messages.enter_your_email')); ?>"
                        class="flex-1 px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-white transition-colors"
                        required
                    >
                    <button type="submit" class="btn-luxury px-6 py-3 rounded-xl font-medium">
                        <?php echo e(__('messages.subscribe')); ?>

                    </button>
                </form>
            </div>
        </div>
        
        <!-- Payment Methods & Security -->
        <div class="mt-12 pt-8 border-t border-gray-800">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex flex-col items-center md:items-start gap-3">
                    <p class="text-gray-400 text-sm">
                        <?php echo e(__('messages.copyright', ['year' => date('Y')])); ?>

                    </p>
                    <div class="flex items-center gap-4">
                        <span class="text-gray-500 text-xs"><?php echo e(__('messages.secure_payment_label')); ?></span>
                        <!-- Visa -->
                        <svg class="h-8 w-auto" viewBox="0 0 48 32" fill="none">
                            <rect width="48" height="32" rx="4" fill="white"/>
                            <path d="M19.2 22H16.4L18.2 10H21L19.2 22Z" fill="#1A1F71"/>
                            <path d="M30.6 10.4C30 10.2 29 10 27.8 10C25 10 23 11.6 23 13.8C23 15.4 24.4 16.2 25.4 16.8C26.4 17.4 26.8 17.8 26.8 18.4C26.8 19.2 25.8 19.6 24.8 19.6C23.4 19.6 22.6 19.4 21.6 19L21.2 18.8L20.6 21.6C21.4 22 22.8 22.4 24.2 22.4C27.2 22.4 29.2 20.8 29.2 18.4C29.2 17.2 28.4 16.2 26.8 15.4C25.8 14.8 25.2 14.4 25.2 13.8C25.2 13.2 25.8 12.6 27 12.6C28.2 12.6 29 12.8 29.6 13L30 13.2L30.6 10.4Z" fill="#1A1F71"/>
                            <path d="M35 10H32.8C32.2 10 31.8 10.2 31.4 10.8L26.4 22H29.4L30 20.4H34.2L34.6 22H37.2L35 10ZM31.4 18L33 13.4L33.8 18H31.4Z" fill="#1A1F71"/>
                            <path d="M14.4 10L11.6 17.4L11.2 15.6C10.6 13.4 8.8 11.6 6.8 10.8L9.6 22H12.6L17.2 10H14.4Z" fill="#1A1F71"/>
                            <path d="M8.6 10H4.4L4.2 10.2C8.2 11.2 10.6 13.6 11.4 16L10.6 10.6C10.4 10.2 10 10 9.6 10H8.6Z" fill="#F7B600"/>
                        </svg>
                        <!-- Mastercard -->
                        <svg class="h-8 w-auto" viewBox="0 0 48 32" fill="none">
                            <rect width="48" height="32" rx="4" fill="white"/>
                            <circle cx="19" cy="16" r="8" fill="#EB001B"/>
                            <circle cx="29" cy="16" r="8" fill="#F79E1B"/>
                            <path d="M24 9.5C25.9 11.2 27 13.5 27 16C27 18.5 25.9 20.8 24 22.5C22.1 20.8 21 18.5 21 16C21 13.5 22.1 11.2 24 9.5Z" fill="#FF5F00"/>
                        </svg>
                        <!-- PayPal -->
                        <svg class="h-8 w-auto" viewBox="0 0 48 32" fill="none">
                            <rect width="48" height="32" rx="4" fill="white"/>
                            <path d="M18.4 8H27.2C30.4 8 33 10.6 33 13.8C33 17 30.4 19.6 27.2 19.6H23.6L22.4 24H19.2L18.4 8Z" fill="#003087"/>
                            <path d="M20.4 10H26.8C29.2 10 31.2 12 31.2 14.4C31.2 16.8 29.2 18.8 26.8 18.8H23.6L22.4 22H20L20.4 10Z" fill="#0070E0"/>
                            <path d="M15.4 8H22.4C24.4 8 26 9.6 26 11.6C26 13.6 24.4 15.2 22.4 15.2H19.6L18.4 20H15.2L15.4 8Z" fill="#003087"/>
                            <path d="M17 10H22C23.6 10 25 11.4 25 13C25 14.6 23.6 16 22 16H19.4L18.2 19H16L17 10Z" fill="#0070E0"/>
                        </svg>
                        <!-- Stripe -->
                        <svg class="h-8 w-auto" viewBox="0 0 48 32" fill="none">
                            <rect width="48" height="32" rx="4" fill="white"/>
                            <path d="M24 14.2C24 13.2 24.8 12.6 26 12.6C27.4 12.6 28.6 13.2 29.4 14L30.6 12.4C29.4 11.2 27.8 10.6 26 10.6C23.4 10.6 21.6 12.2 21.6 14.6C21.6 17.4 25.4 16.8 25.4 18.4C25.4 19.4 24.6 20 23.2 20C21.6 20 20.2 19.2 19.2 18L18 19.6C19.2 21 21.2 22 23.2 22C25.8 22 27.8 20.4 27.8 18C27.8 15 24 15.6 24 14.2Z" fill="#635BFF"/>
                            <path d="M33.8 10.8H31.4V21.8H33.8V10.8Z" fill="#635BFF"/>
                            <path d="M35.6 10.8H38V21.8H35.6V10.8Z" fill="#635BFF"/>
                            <path d="M42.4 13.8C43.6 13.8 44.4 14.4 44.8 15L46.4 13.6C45.6 12.4 44.2 11.8 42.4 11.8C39.8 11.8 38 13.6 38 16.2C38 18.8 39.8 20.6 42.4 20.6C44.2 20.6 45.6 20 46.4 18.8L44.8 17.4C44.4 18 43.6 18.6 42.4 18.6C41.2 18.6 40.4 17.8 40.4 16.2C40.4 14.6 41.2 13.8 42.4 13.8Z" fill="#635BFF"/>
                        </svg>
                    </div>
                </div>

                <div class="flex items-center space-x-6">
                    <!-- Language Switcher -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-gray-400 hover:text-white transition-colors text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                            </svg>
                            <span class="text-xs font-medium"><?php echo e(app()->getLocale() === 'en' ? __('messages.english') : __('messages.myanmar')); ?></span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute bottom-full mb-2 right-0 w-28 bg-white border border-gray-200 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <?php if(app()->getLocale() === 'en'): ?>
                                <button onclick="switchLanguage('my')" class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl transition-colors">
                                    <?php echo e(__('messages.myanmar')); ?>

                                </button>
                            <?php else: ?>
                                <button onclick="switchLanguage('en')" class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl transition-colors">
                                    <?php echo e(__('messages.english')); ?>

                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Currency Switcher -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-gray-400 hover:text-white transition-colors text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs font-medium currency-display"><?php echo e($currentCurrency ?? 'USD'); ?></span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute bottom-full mb-2 right-0 w-28 bg-white border border-gray-200 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <?php $__currentLoopData = $supportedCurrencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($code !== ($currentCurrency ?? 'USD')): ?>
                                    <button onclick="switchCurrency('<?php echo e($code); ?>')" class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl transition-colors">
                                        <?php echo e($config['symbol']); ?> <?php echo e($code); ?>

                                    </button>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\partials\footer.blade.php ENDPATH**/ ?>