<?php $__env->startSection('title', $user->name . ' - Profile - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', 'View and manage your Panchi Gallery profile. Update your information, view your collection, and track your art journey.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Profile Header -->
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
            <!-- Profile Picture -->
            <div class="relative group">
                <img src="<?php echo e($user->avatar_url); ?>" 
                     alt="<?php echo e($user->name); ?>" 
                     class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover">
                <button onclick="document.getElementById('avatar-upload').click()" 
                        class="absolute bottom-0 right-0 bg-white text-indigo-600 p-2 rounded-full shadow-lg hover:bg-indigo-50 transition-colors opacity-0 group-hover:opacity-100"
                        title="Change Avatar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </button>
                <input type="file" id="avatar-upload" class="hidden" accept="image/*">
            </div>
            
            <!-- Profile Info -->
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl font-bold mb-2"><?php echo e($user->name); ?></h1>
                <p class="text-lg text-indigo-100 mb-4 flex items-center justify-center md:justify-start gap-2">
                    <?php if($user->isArtist()): ?>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                        Artist
                    <?php elseif($user->isCollector()): ?>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Art Collector
                    <?php else: ?>
                        Member
                    <?php endif; ?>
                    <?php if(!$user->is_approved && !$user->isAdmin()): ?>
                        <span class="bg-yellow-400 text-yellow-900 px-2 py-1 rounded-full text-xs font-medium">
                            Pending Approval
                        </span>
                    <?php endif; ?>
                    <?php if($user->is_verified): ?>
                        <span class="bg-blue-400 text-white px-2 py-1 rounded-full text-xs font-medium flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Verified
                        </span>
                    <?php endif; ?>
                </p>
                
                <div class="flex flex-col md:flex-row gap-4 text-sm text-indigo-100">
                    <?php if($user->email): ?>
                        <div class="flex items-center justify-center md:justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <?php echo e($user->email); ?>

                        </div>
                    <?php endif; ?>
                    <?php if($user->phone): ?>
                        <div class="flex items-center justify-center md:justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <?php echo e($user->phone); ?>

                        </div>
                    <?php endif; ?>
                    <?php if($user->location): ?>
                        <div class="flex items-center justify-center md:justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <?php echo e($user->location); ?>

                        </div>
                    <?php endif; ?>
                    <?php if($user->website): ?>
                        <div class="flex items-center justify-center md:justify-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                            <a href="<?php echo e($user->website); ?>" target="_blank" class="hover:text-white transition-colors">
                                <?php echo e(Str::replace(['http://', 'https://'], '', $user->website)); ?>

                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col space-y-3">
                <a href="<?php echo e(route('profile.edit')); ?>" class="bg-white text-indigo-600 px-6 py-2 rounded-lg font-medium hover:bg-indigo-50 transition-colors text-center flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Profile
                </a>
                <?php if($user->isArtist()): ?>
                    <a href="<?php echo e(route('artist.dashboard')); ?>" class="border-2 border-white text-white px-6 py-2 rounded-lg font-medium hover:bg-white hover:text-indigo-600 transition-colors text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Artist Dashboard
                    </a>
                <?php elseif($user->isCollector()): ?>
                    <a href="<?php echo e(route('collector.dashboard')); ?>" class="border-2 border-white text-white px-6 py-2 rounded-lg font-medium hover:bg-white hover:text-indigo-600 transition-colors text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Collector Dashboard
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Profile Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        <?php if(session('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-8 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-12">
            <?php if($user->isArtist()): ?>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                    <div class="text-3xl font-bold text-indigo-600 mb-1"><?php echo e($stats['artworks_created'] ?? 0); ?></div>
                    <div class="text-sm text-gray-600">Artworks</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                    <div class="text-3xl font-bold text-green-600 mb-1"><?php echo e($stats['artworks_approved'] ?? 0); ?></div>
                    <div class="text-sm text-gray-600">Approved</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                    <div class="text-3xl font-bold text-purple-600 mb-1"><?php echo e($stats['artworks_sold'] ?? 0); ?></div>
                    <div class="text-sm text-gray-600">Sold</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                    <div class="text-3xl font-bold text-blue-600 mb-1"><?php echo e(number_format($stats['total_views'] ?? 0)); ?></div>
                    <div class="text-sm text-gray-600">Views</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                    <div class="text-3xl font-bold text-pink-600 mb-1"><?php echo e($stats['followers_count'] ?? 0); ?></div>
                    <div class="text-sm text-gray-600">Followers</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                    <div class="text-3xl font-bold text-orange-600 mb-1">$<?php echo e(number_format($stats['total_sales'] ?? 0, 0)); ?></div>
                    <div class="text-sm text-gray-600">Earnings</div>
                </div>
            <?php elseif($user->isCollector()): ?>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                    <div class="text-3xl font-bold text-indigo-600 mb-1"><?php echo e($stats['artworks_owned'] ?? 0); ?></div>
                    <div class="text-sm text-gray-600">Owned</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                    <div class="text-3xl font-bold text-green-600 mb-1"><?php echo e($stats['total_purchases'] ?? 0); ?></div>
                    <div class="text-sm text-gray-600">Purchases</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                    <div class="text-3xl font-bold text-purple-600 mb-1">$<?php echo e(number_format($stats['total_spent'] ?? 0, 0)); ?></div>
                    <div class="text-sm text-gray-600">Spent</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                    <div class="text-3xl font-bold text-pink-600 mb-1"><?php echo e($stats['wishlist_count'] ?? 0); ?></div>
                    <div class="text-sm text-gray-600">Wishlist</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                    <div class="text-3xl font-bold text-blue-600 mb-1"><?php echo e($stats['following_count'] ?? 0); ?></div>
                    <div class="text-sm text-gray-600">Following</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                    <div class="text-3xl font-bold text-orange-600 mb-1"><?php echo e($stats['resales_active'] ?? 0); ?></div>
                    <div class="text-sm text-gray-600">Resales</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tabs Navigation -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="border-b border-gray-200">
                <nav class="flex flex-wrap gap-2 px-6" aria-label="Tabs">
                    <button onclick="showTab('about')" 
                            class="tab-button py-4 px-4 border-b-2 font-medium text-sm border-indigo-500 text-indigo-600 transition-colors"
                            data-tab="about">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            About
                        </span>
                    </button>
                    <button onclick="showTab('artworks')" 
                            class="tab-button py-4 px-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors"
                            data-tab="artworks">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <?php echo e($user->isArtist() ? 'My Artworks' : 'My Collection'); ?>

                        </span>
                    </button>
                    <button onclick="showTab('activity')" 
                            class="tab-button py-4 px-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors"
                            data-tab="activity">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Recent Activity
                        </span>
                    </button>
                    <button onclick="showTab('settings')" 
                            class="tab-button py-4 px-4 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors"
                            data-tab="settings">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Settings
                        </span>
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- About Tab -->
                <div id="about-tab" class="tab-content">
                    <div class="max-w-3xl">
                        <!-- Bio Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                About Me
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <?php if($user->bio): ?>
                                    <p class="text-gray-700 leading-relaxed"><?php echo e($user->bio); ?></p>
                                <?php else: ?>
                                    <p class="text-gray-500 italic">No biography added yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if($user->isArtist()): ?>
                            <!-- Artist Statement -->
                            <?php if($user->artist_statement): ?>
                                <div class="mb-8">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                        </svg>
                                        Artist Statement
                                    </h3>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-gray-700 leading-relaxed"><?php echo e($user->artist_statement); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Specialization -->
                            <?php if($user->specialization): ?>
                                <div class="mb-6">
                                    <h4 class="text-md font-semibold text-gray-900 mb-2">Specialization</h4>
                                    <span class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
                                        <?php echo e($user->specialization); ?>

                                    </span>
                                </div>
                            <?php endif; ?>

                            <!-- Education -->
                            <?php if($user->education): ?>
                                <div class="mb-6">
                                    <h4 class="text-md font-semibold text-gray-900 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.077 12.077 0 01.665-6.479L12 14z"></path>
                                        </svg>
                                        Education
                                    </h4>
                                    <p class="text-gray-600"><?php echo e($user->education); ?></p>
                                </div>
                            <?php endif; ?>

                            <!-- Exhibitions -->
                            <?php if($user->exhibitions): ?>
                                <div class="mb-6">
                                    <h4 class="text-md font-semibold text-gray-900 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Exhibitions
                                    </h4>
                                    <p class="text-gray-600"><?php echo e($user->exhibitions); ?></p>
                                </div>
                            <?php endif; ?>

                            <!-- Awards -->
                            <?php if($user->awards): ?>
                                <div class="mb-6">
                                    <h4 class="text-md font-semibold text-gray-900 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                        </svg>
                                        Awards
                                    </h4>
                                    <p class="text-gray-600"><?php echo e($user->awards); ?></p>
                                </div>
                            <?php endif; ?>

                            <!-- Years Active -->
                            <?php if($user->years_active): ?>
                                <div class="mb-6">
                                    <h4 class="text-md font-semibold text-gray-900 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Years Active
                                    </h4>
                                    <p class="text-gray-600"><?php echo e($user->years_active); ?> years</p>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Member Since -->
                        <div class="pt-6 border-t border-gray-200">
                            <p class="text-sm text-gray-500 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Member since <?php echo e($user->created_at->format('F Y')); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <!-- Artworks Tab -->
                <div id="artworks-tab" class="tab-content hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <?php echo e($user->isArtist() ? 'My Artworks' : 'My Collection'); ?>

                        </h3>
                        <?php if($user->isArtist()): ?>
                            <a href="<?php echo e(route('artist.artworks.create')); ?>" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Artwork
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($user->isArtist()): ?>
                        <?php if(isset($recentActivity['artworks']) && count($recentActivity['artworks']) > 0): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php $__currentLoopData = $recentActivity['artworks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artwork): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('public.artworks.show', $artwork)); ?>" class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all group">
                                        <div class="relative h-48 overflow-hidden">
                                            <img src="<?php echo e($artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>" 
                                                 alt="<?php echo e($artwork->title); ?>" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            <?php if($artwork->status === 'pending'): ?>
                                                <span class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 px-2 py-1 rounded-full text-xs font-medium">Pending</span>
                                            <?php elseif($artwork->status === 'approved'): ?>
                                                <span class="absolute top-2 right-2 bg-green-400 text-green-900 px-2 py-1 rounded-full text-xs font-medium">Approved</span>
                                            <?php elseif($artwork->status === 'sold'): ?>
                                                <span class="absolute top-2 right-2 bg-gray-800 text-white px-2 py-1 rounded-full text-xs font-medium">Sold</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="p-4">
                                            <h4 class="font-semibold text-gray-900 mb-1"><?php echo e($artwork->title); ?></h4>
                                            <p class="text-sm text-gray-600 mb-2"><?php echo e($artwork->category->name ?? 'Uncategorized'); ?></p>
                                            <div class="flex justify-between items-center">
                                                <span class="font-bold text-indigo-600">$<?php echo e(number_format($artwork->price, 2)); ?></span>
                                                <span class="text-sm text-gray-500 flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <?php echo e($artwork->likes_count ?? 0); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="mt-6 text-center">
                                <a href="<?php echo e(route('artist.artworks')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium flex items-center justify-center gap-2">
                                    View All Artworks
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-12 bg-gray-50 rounded-xl">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-500 mb-4">You haven't created any artworks yet.</p>
                                <a href="<?php echo e(route('artist.artworks.create')); ?>" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Create Your First Artwork
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php elseif($user->isCollector()): ?>
                        <?php if(isset($recentActivity['collection']) && count($recentActivity['collection']) > 0): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php $__currentLoopData = $recentActivity['collection']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ownership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('public.artworks.show', $ownership->artwork)); ?>" class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all group">
                                        <div class="relative h-48 overflow-hidden">
                                            <img src="<?php echo e($ownership->artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>" 
                                                 alt="<?php echo e($ownership->artwork->title); ?>" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                        <div class="p-4">
                                            <h4 class="font-semibold text-gray-900 mb-1"><?php echo e($ownership->artwork->title); ?></h4>
                                            <p class="text-sm text-gray-600 mb-2">by <?php echo e($ownership->artwork->artist->name ?? 'Unknown'); ?></p>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-green-600 flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Owned
                                                </span>
                                                <span class="text-xs text-gray-400"><?php echo e($ownership->acquired_at?->format('M Y') ?? 'Recently'); ?></span>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="mt-6 text-center">
                                <a href="<?php echo e(route('collector.artworks')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium flex items-center justify-center gap-2">
                                    View Full Collection
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-12 bg-gray-50 rounded-xl">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <p class="text-gray-500 mb-4">You don't own any artworks yet.</p>
                                <a href="<?php echo e(route('public.artworks.index')); ?>" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Browse Artworks
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Activity Tab -->
                <div id="activity-tab" class="tab-content hidden">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Recent Activity
                    </h3>
                    
                    <?php if($user->isArtist()): ?>
                        <?php if(isset($recentActivity['sales']) && count($recentActivity['sales']) > 0): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $recentActivity['sales']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900"><?php echo e($sale->artwork->title ?? 'Artwork Sold'); ?></h4>
                                                    <p class="text-sm text-gray-600">Sold to <?php echo e($sale->buyer->name ?? 'Buyer'); ?></p>
                                                    <p class="text-xs text-gray-500"><?php echo e($sale->completed_at?->format('M d, Y') ?? 'Recently'); ?></p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold text-green-600">$<?php echo e(number_format($sale->seller_earnings ?? $sale->amount ?? 0, 2)); ?></div>
                                                <div class="text-sm text-gray-500">Earnings</div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="mt-6 text-center">
                                <a href="<?php echo e(route('artist.sales')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">View All Sales →</a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-12 bg-gray-50 rounded-xl">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <p class="text-gray-500 mb-4">No sales activity yet.</p>
                                <a href="<?php echo e(route('public.artworks.index')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">View Gallery →</a>
                            </div>
                        <?php endif; ?>
                    <?php elseif($user->isCollector()): ?>
                        <?php if(isset($recentActivity['purchases']) && count($recentActivity['purchases']) > 0): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $recentActivity['purchases']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900"><?php echo e($purchase->artwork->title ?? 'Artwork Purchased'); ?></h4>
                                                    <p class="text-sm text-gray-600">by <?php echo e($purchase->artwork->artist->name ?? 'Artist'); ?></p>
                                                    <p class="text-xs text-gray-500"><?php echo e($purchase->completed_at?->format('M d, Y') ?? $purchase->created_at?->format('M d, Y') ?? 'Recently'); ?></p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold text-gray-900">$<?php echo e(number_format($purchase->amount ?? 0, 2)); ?></div>
                                                <div class="text-sm text-green-600">Completed</div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="mt-6 text-center">
                                <a href="<?php echo e(route('collector.purchases')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">View All Purchases →</a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-12 bg-gray-50 rounded-xl">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-gray-500 mb-4">No purchases yet.</p>
                                <a href="<?php echo e(route('public.artworks.index')); ?>" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Browse Artworks
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Settings Tab -->
                <div id="settings-tab" class="tab-content hidden">
                    <div class="max-w-2xl">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Account Settings
                        </h3>
                        
                        <!-- Quick Actions -->
                        <div class="space-y-4 mb-8">
                            <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Edit Profile</p>
                                        <p class="text-sm text-gray-500">Update your personal information</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>

                            <a href="<?php echo e(route('wishlist.index')); ?>" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">My Wishlist</p>
                                        <p class="text-sm text-gray-500">View saved artworks</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>

                            <?php if($user->isArtist()): ?>
                                <a href="<?php echo e(route('artist.earnings')); ?>" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">Earnings</p>
                                            <p class="text-sm text-gray-500">View your sales and payouts</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>

                                <a href="<?php echo e(route('artist.followers')); ?>" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">Followers</p>
                                            <p class="text-sm text-gray-500">View your followers</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            <?php elseif($user->isCollector()): ?>
                                <a href="<?php echo e(route('collector.following')); ?>" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">Following</p>
                                            <p class="text-sm text-gray-500">Artists you follow</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Account Actions -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-md font-semibold text-gray-900 mb-4">Account Actions</h4>
                            <div class="space-y-3">
                                <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Tab functionality
    function showTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });
        
        // Remove active state from all buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('border-indigo-500', 'text-indigo-600');
            button.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Show selected tab
        const selectedTab = document.getElementById(tabName + '-tab');
        if (selectedTab) {
            selectedTab.classList.remove('hidden');
        }
        
        // Add active state to clicked button
        const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
        if (activeButton) {
            activeButton.classList.remove('border-transparent', 'text-gray-500');
            activeButton.classList.add('border-indigo-500', 'text-indigo-600');
        }
        
        // Save active tab to session storage
        sessionStorage.setItem('profile_active_tab', tabName);
    }
    
    // Restore active tab on page load
    document.addEventListener('DOMContentLoaded', function() {
        const savedTab = sessionStorage.getItem('profile_active_tab');
        if (savedTab && document.getElementById(savedTab + '-tab')) {
            showTab(savedTab);
        }
    });
    
    // Avatar upload with loading state
    const avatarUpload = document.getElementById('avatar-upload');
    if (avatarUpload) {
        avatarUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    showToast('Please select a valid image file (JPEG, PNG, GIF, WebP)', 'error');
                    return;
                }
                
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    showToast('File size must be less than 2MB', 'error');
                    return;
                }
                
                const formData = new FormData();
                formData.append('avatar', file);
                
                // Show loading toast
                showToast('Uploading avatar...', 'info');
                
                fetch('<?php echo e(route('profile.update-avatar')); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Avatar updated successfully!', 'success');
                        // Update avatar image without reload
                        const avatarImg = document.querySelector('img[alt="<?php echo e($user->name); ?>"]');
                        if (avatarImg && data.avatar_url) {
                            avatarImg.src = data.avatar_url + '?t=' + new Date().getTime();
                        }
                    } else {
                        showToast(data.message || 'Failed to update avatar', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred while uploading', 'error');
                });
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\profile\show.blade.php ENDPATH**/ ?>