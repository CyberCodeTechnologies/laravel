<?php $__env->startSection('title', __('messages.artist_dashboard.sales_certificates') . ' - ' . __('messages.artist_dashboard.title') . ' - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', __('messages.artist_dashboard.sales_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<!-- Dashboard Header -->
<section class="bg-gradient-to-r from-gray-900 to-black text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-serif text-3xl md:text-4xl font-bold mb-2">
                    <?php echo e(__('messages.artist_dashboard.sales_title', ['name' => auth()->user()->name])); ?>

                </h1>
                <p class="text-gray-300">
                    <?php echo e(__('messages.artist_dashboard.sales_subtitle')); ?>

                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'secondary','onclick' => 'window.location.href=\''.e(route('artist.earnings')).'\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','onclick' => 'window.location.href=\''.e(route('artist.earnings')).'\'']); ?>
                    <?php echo e(__('messages.artist_dashboard.view_earnings')); ?>

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
                <div class="text-3xl font-bold mb-2"><?php echo e($totalSales ?? 0); ?></div>
                <div class="text-gray-300"><?php echo e(__('messages.artist_profile.total_sales')); ?></div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">$<?php echo e(number_format($totalRevenue ?? 0)); ?></div>
                <div class="text-gray-300"><?php echo e(__('messages.artist_dashboard.total_revenue')); ?></div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2">$<?php echo e(number_format($monthlyRevenue ?? 0)); ?></div>
                <div class="text-gray-300"><?php echo e(__('messages.artist_dashboard.this_month')); ?></div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold mb-2"><?php echo e(auth()->user()->artworks()->where('status', 'sold')->count()); ?></div>
                <div class="text-gray-300"><?php echo e(__('messages.artist_dashboard.sold_artworks')); ?></div>
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
                <a href="<?php echo e(route('artist.analytics')); ?>" id="analytics-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    <?php echo e(__('messages.artist_dashboard.analytics')); ?>

                </a>
                <a href="<?php echo e(route('artist.orders')); ?>" id="orders-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    <?php echo e(__('messages.artist_dashboard.orders')); ?>

                </a>
                <button id="sales-tab" class="py-4 px-1 border-b-2 border-black font-medium text-black">
                    <?php echo e(__('messages.artist_dashboard.sales_certificates')); ?>

                </button>
                <a href="<?php echo e(route('artist.profile')); ?>" id="profile-tab" class="py-4 px-1 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                    <?php echo e(__('messages.artist_dashboard.artist_profile')); ?>

                </a>
            </nav>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Sales Table -->
        <div id="sales-content" class="tab-content">
            <div class="mb-8">
                <h2 class="font-serif text-2xl font-bold text-gray-900"><?php echo e(__('messages.artist_dashboard.sales_history')); ?></h2>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <?php if(isset($sales) && count($sales) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><?php echo e(__('messages.artist_dashboard.artwork')); ?></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><?php echo e(__('messages.artist_dashboard.buyer')); ?></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><?php echo e(__('messages.artist_dashboard.date')); ?></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><?php echo e(__('messages.artist_dashboard.price')); ?></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><?php echo e(__('messages.artist_dashboard.status')); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="<?php echo e($sale->artwork->image_url ?? asset('images/placeholder-artwork.jpg')); ?>" alt="" class="w-10 h-10 rounded object-cover">
                                            <span class="font-medium text-gray-900"><?php echo e($sale->artwork->title ?? 'Artwork'); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700"><?php echo e($sale->buyer->name ?? 'Unknown'); ?></td>
                                    <td class="px-6 py-4 text-gray-500"><?php echo e($sale->created_at->format('M d, Y')); ?></td>
                                    <td class="px-6 py-4 font-medium text-gray-900">$<?php echo e(number_format($sale->amount ?? $sale->price, 2)); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                            <?php echo e($sale->status === 'completed' ? 'bg-green-100 text-green-800' : ''); ?>

                                            <?php echo e($sale->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                            <?php echo e($sale->status === 'refunded' ? 'bg-red-100 text-red-800' : ''); ?>">
                                            <?php echo e(ucfirst($sale->status)); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if(method_exists($sales, 'links')): ?>
                    <div class="p-4 border-t border-gray-100">
                        <?php echo e($sales->links()); ?>

                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No sales yet</h3>
                    <p class="text-gray-500">Your sales will appear here once collectors purchase your artworks.</p>
                </div>
            <?php endif; ?>
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

<?php $__env->startPush('scripts'); ?>
<script>
function switchTab(tabName) {
    console.log('Switched to tab:', tabName);
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\artist\sales.blade.php ENDPATH**/ ?>