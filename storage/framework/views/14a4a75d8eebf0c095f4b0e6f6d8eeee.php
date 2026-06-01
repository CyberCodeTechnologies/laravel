<?php $__env->startSection('title', __('messages.artist_artworks.title', ['default' => 'My Artworks']) . ' - Panchi Gallery'); ?>
<?php $__env->startSection('meta-description', __('messages.artist_artworks.meta_description', ['default' => 'Manage your artwork portfolio on Panchi Gallery. View, edit, and track all your artworks in one place.'])); ?>

<?php $__env->startSection('content'); ?>
<!-- Dashboard Header -->
<section class="bg-gradient-to-r from-gray-900 to-black text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="font-serif text-3xl md:text-4xl font-bold mb-2">
                    <?php echo e(__('messages.artist_artworks.title', ['default' => 'My Artworks'])); ?>

                </h1>
                <p class="text-gray-300">
                    <?php echo e(__('messages.artist_artworks.subtitle', ['default' => 'Manage your portfolio'])); ?>

                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'secondary','href' => ''.e(route('artist.artworks.create')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','href' => ''.e(route('artist.artworks.create')).'']); ?>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <?php echo e(__('messages.artist_artworks.add_new', ['default' => 'Add New Artwork'])); ?>

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
    </div>
</section>

<!-- Main Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <?php if(session('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <div class="flex flex-wrap gap-3">
                <a href="<?php echo e(route('artist.artworks')); ?>" class="px-4 py-2 rounded-lg font-medium transition <?php echo e(!request('status') ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>">
                    <?php echo e(__('messages.artist_artworks.filter_all', ['default' => 'All'])); ?>

                </a>
                <a href="<?php echo e(route('artist.artworks', ['status' => 'approved'])); ?>" class="px-4 py-2 rounded-lg font-medium transition <?php echo e(request('status') === 'approved' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>">
                    <?php echo e(__('messages.artist_artworks.filter_published', ['default' => 'Published'])); ?>

                </a>
                <a href="<?php echo e(route('artist.artworks', ['status' => 'pending'])); ?>" class="px-4 py-2 rounded-lg font-medium transition <?php echo e(request('status') === 'pending' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>">
                    <?php echo e(__('messages.artist_artworks.filter_pending', ['default' => 'Pending'])); ?>

                </a>
                <a href="<?php echo e(route('artist.artworks', ['status' => 'sold'])); ?>" class="px-4 py-2 rounded-lg font-medium transition <?php echo e(request('status') === 'sold' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>">
                    <?php echo e(__('messages.artist_artworks.filter_sold', ['default' => 'Sold'])); ?>

                </a>
            </div>
        </div>

        <!-- Artworks Grid -->
        <?php if(isset($artworks) && count($artworks) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $artworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artwork): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden group">
                        <div class="relative">
                            <img src="<?php echo e($artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>" alt="<?php echo e($artwork->title); ?>" class="w-full h-56 object-cover">
                            <div class="absolute top-3 right-3">
                                <span class="px-3 py-1 rounded-full text-xs font-medium 
                                    <?php echo e($artwork->status === 'approved' ? 'bg-green-100 text-green-800' : ''); ?>

                                    <?php echo e($artwork->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                    <?php echo e($artwork->status === 'sold' ? 'bg-gray-100 text-gray-800' : ''); ?>">
                                    <?php echo e(ucfirst($artwork->status)); ?>

                                </span>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-gray-900 text-lg mb-1"><?php echo e($artwork->title); ?></h3>
                            <p class="text-sm text-gray-500 mb-3"><?php echo e($artwork->category->name ?? __('messages.artist_artworks.uncategorized', ['default' => 'Uncategorized'])); ?></p>
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-gray-900">$<?php echo e(number_format($artwork->price, 2)); ?></span>
                                <div class="flex gap-2">
                                    <a href="<?php echo e(route('artist.artworks.edit', $artwork)); ?>" class="text-gray-600 hover:text-black transition p-1 rounded hover:bg-gray-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="<?php echo e(route('artist.artworks.delete', $artwork)); ?>" method="POST" class="inline" onsubmit="return confirm('<?php echo e(__('messages.artist_artworks.delete_confirm', ['default' => 'Are you sure you want to delete this artwork?'])); ?>')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition p-1 rounded hover:bg-red-50">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <?php if(method_exists($artworks, 'links')): ?>
                <div class="mt-8">
                    <?php echo e($artworks->links()); ?>

                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2"><?php echo e(__('messages.artist_artworks.empty_title', ['default' => 'No artworks yet'])); ?></h3>
                <p class="text-gray-500 mb-6"><?php echo e(__('messages.artist_artworks.empty_description', ['default' => 'Start building your portfolio by adding your first artwork.'])); ?></p>
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'primary','href' => ''.e(route('artist.artworks.create')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','href' => ''.e(route('artist.artworks.create')).'']); ?>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <?php echo e(__('messages.artist_artworks.add_new', ['default' => 'Add New Artwork'])); ?>

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
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\artist\artworks.blade.php ENDPATH**/ ?>