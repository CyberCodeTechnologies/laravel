<?php $__env->startSection('title', __('messages.artist_dashboard.analytics') . ' - ' . __('messages.artist_dashboard.title') . ' - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', __('messages.artist_dashboard.analytics_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<!-- Dashboard Header -->
<section class="bg-gradient-to-r from-gray-900 to-black text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-serif text-3xl md:text-4xl font-bold mb-2">
                    <?php echo e(__('messages.artist_dashboard.analytics_title', ['name' => auth()->user()->name])); ?>

                </h1>
                <p class="text-gray-300">
                    <?php echo e(__('messages.artist_dashboard.analytics_subtitle')); ?>

                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'secondary','onclick' => 'window.location.href=\''.e(route('artist.artworks.create')).'\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','onclick' => 'window.location.href=\''.e(route('artist.artworks.create')).'\'']); ?>
                    <?php echo e(__('messages.artist_dashboard.upload_new_artwork')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12">
            <div class="text-center">
                <div class="text-3xl font-bold mb-2"><?php echo e(auth()->user()->artworks()->count()); ?></div>
                <div class="text-gray-300"><?php echo e(__('messages.artist_dashboard.total_artworks')); ?></div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2"><?php echo e(auth()->user()->artworks()->sum('views_count') ?? 0); ?></div>
                <div class="text-gray-300"><?php echo e(__('messages.artist_dashboard.total_views')); ?></div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">$<?php echo e(number_format(auth()->user()->sales()->where('status', 'completed')->sum('seller_earnings') ?? 0)); ?></div>
                <div class="text-gray-300"><?php echo e(__('messages.artist_profile.total_sales')); ?></div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2"><?php echo e(auth()->user()->sales()->where('status', 'completed')->count()); ?></div>
                <div class="text-gray-300"><?php echo e(__('messages.artist_dashboard.completed_orders')); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200 mb-8">
            <nav class="flex space-x-8">
                <a href="<?php echo e(route('dashboard')); ?>" id="artworks-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    <?php echo e(__('messages.artist_dashboard.my_artworks')); ?>

                </a>
                <button id="analytics-tab" class="py-4 px-1 border-b-2 border-black font-medium text-black">
                    <?php echo e(__('messages.artist_dashboard.analytics')); ?>

                </button>
                <a href="<?php echo e(route('artist.orders')); ?>" id="orders-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    <?php echo e(__('messages.artist_dashboard.orders')); ?>

                </a>
                <a href="<?php echo e(route('artist.sales')); ?>" id="sales-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    <?php echo e(__('messages.artist_dashboard.sales_certificates')); ?>

                </a>
                <a href="<?php echo e(route('artist.profile')); ?>" id="profile-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    <?php echo e(__('messages.artist_dashboard.artist_profile')); ?>

                </a>
            </nav>
        </div>

        <!-- Analytics Content -->
        <div id="analytics-content" class="tab-content">
            <!-- Detailed Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-gray-600"><?php echo e(__('messages.artist_dashboard.profile_views')); ?></h4>
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900"><?php echo e(auth()->user()->profile_views ?? 0); ?></div>
                    <div class="text-sm text-gray-500 mt-1"><?php echo e(__('messages.artist_dashboard.artist_profile_visits')); ?></div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-gray-600"><?php echo e(__('messages.artist_dashboard.artwork_views')); ?></h4>
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900"><?php echo e(auth()->user()->artworks()->sum('views_count') ?? 0); ?></div>
                    <div class="text-sm text-gray-500 mt-1"><?php echo e(__('messages.artist_dashboard.total_artwork_impressions')); ?></div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-gray-600">Conversion Rate</h4>
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">
                        <?php
                            $views = auth()->user()->artworks()->sum('views_count') ?? 0;
                            $sales = auth()->user()->sales()->where('status', 'completed')->count() ?? 0;
                            $rate = $views > 0 ? round(($sales / $views) * 100, 2) : 0;
                        ?>
                        <?php echo e($rate); ?>%
                    </div>
                    <div class="text-sm text-gray-500 mt-1">Views to sales ratio</div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-gray-600">Avg. Sale Value</h4>
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08.402-2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">
                        <?php
                            $totalSales = auth()->user()->sales()->where('status', 'completed')->sum('amount') ?? 0;
                            $saleCount = auth()->user()->sales()->where('status', 'completed')->count() ?? 0;
                            $avg = $saleCount > 0 ? round($totalSales / $saleCount, 2) : 0;
                        ?>
                        $<?php echo e(number_format($avg, 2)); ?>

                    </div>
                    <div class="text-sm text-gray-500 mt-1">Per transaction</div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Sales Chart -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="font-serif text-xl font-bold text-gray-900 mb-6">Sales Overview</h3>
                    <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <p class="text-gray-500">Sales chart will be displayed here</p>
                        </div>
                    </div>
                </div>

                <!-- Views by Category -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="font-serif text-xl font-bold text-gray-900 mb-6">Views by Category</h3>
                    <div class="space-y-4">
                        <?php if(isset($viewsByCategory) && count($viewsByCategory) > 0): ?>
                            <?php $__currentLoopData = $viewsByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                    <span class="text-gray-700"><?php echo e($category->name); ?></span>
                                    <div class="flex items-center">
                                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="bg-black h-2 rounded-full" style="width: <?php echo e(min(100, ($category->total_views / max($viewsByCategory->max('total_views'), 1)) * 100)); ?>%"></div>
                                        </div>
                                        <span class="font-semibold text-gray-900 w-16 text-right"><?php echo e($category->total_views); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <p class="text-gray-500 text-center py-4">No category data available</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Top Performing Artworks Table -->
            <div class="mb-8">
                <h2 class="font-serif text-2xl font-bold text-gray-900 mb-6">Top Performing Artworks</h2>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artwork</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Likes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if(isset($topArtworks) && count($topArtworks) > 0): ?>
                                <?php $__currentLoopData = $topArtworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artwork): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img src="<?php echo e($artwork->primary_image); ?>"
                                                     alt="<?php echo e($artwork->title); ?>"
                                                     class="w-12 h-12 rounded-lg object-cover mr-3">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900"><?php echo e($artwork->title); ?></div>
                                                    <div class="text-sm text-gray-500">ID: #<?php echo e(str_pad($artwork->id, 6, '0', STR_PAD_LEFT)); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            $<?php echo e(number_format($artwork->price)); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo e($artwork->views_count ?? 0); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo e($artwork->transactions_count ?? 0); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo e($artwork->likes_count ?? 0); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                                $score = ($artwork->views_count * 0.5) + ($artwork->transactions_count * 10) + ($artwork->likes_count * 2);
                                                if ($score >= 100) {
                                                    $performance = ['label' => 'High', 'class' => 'bg-green-100 text-green-800'];
                                                } elseif ($score >= 50) {
                                                    $performance = ['label' => 'Medium', 'class' => 'bg-yellow-100 text-yellow-800'];
                                                } else {
                                                    $performance = ['label' => 'Low', 'class' => 'bg-gray-100 text-gray-800'];
                                                }
                                            ?>
                                            <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo e($performance['class']); ?>">
                                                <?php echo e($performance['label']); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        No artwork data available. Upload artworks to see analytics.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Back to Dashboard -->
            <div class="mt-8">
                <a href="<?php echo e(route('dashboard')); ?>" class="text-black hover:text-gray-700 font-medium">
                    &larr; Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function switchTab(tabName) {
    // This is a placeholder for tab switching if needed
    console.log('Switched to tab:', tabName);
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\artist\analytics.blade.php ENDPATH**/ ?>