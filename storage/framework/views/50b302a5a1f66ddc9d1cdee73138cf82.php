


<?php if(isset($items) && count($items) > 0): ?>
    <nav class="bg-white border-b border-gray-200" aria-label="Breadcrumb">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($loop->first): ?>
                        <li>
                            <?php if(isset($item['url'])): ?>
                                <a href="<?php echo e($item['url']); ?>" class="hover:text-indigo-600 transition-colors">
                                    <?php if(isset($item['icon'])): ?>
                                        <i class="<?php echo e($item['icon']); ?> mr-1"></i>
                                    <?php endif; ?>
                                    <?php echo e($item['title']); ?>

                                </a>
                            <?php else: ?>
                                <span class="text-gray-900 font-medium">
                                    <?php if(isset($item['icon'])): ?>
                                        <i class="<?php echo e($item['icon']); ?> mr-1"></i>
                                    <?php endif; ?>
                                    <?php echo e($item['title']); ?>

                                </span>
                            <?php endif; ?>
                        </li>
                    <?php elseif($loop->last): ?>
                        <li class="text-gray-900 font-medium">
                            <?php if(isset($item['icon'])): ?>
                                <i class="<?php echo e($item['icon']); ?> mr-1"></i>
                            <?php endif; ?>
                            <?php echo e($item['title']); ?>

                        </li>
                    <?php else: ?>
                        <li class="flex items-center">
                            <i class="fas fa-chevron-right text-xs text-gray-400 mx-2"></i>
                            <?php if(isset($item['url'])): ?>
                                <a href="<?php echo e($item['url']); ?>" class="hover:text-indigo-600 transition-colors">
                                    <?php if(isset($item['icon'])): ?>
                                        <i class="<?php echo e($item['icon']); ?> mr-1"></i>
                                    <?php endif; ?>
                                    <?php echo e($item['title']); ?>

                                </a>
                            <?php else: ?>
                                <span>
                                    <?php if(isset($item['icon'])): ?>
                                        <i class="<?php echo e($item['icon']); ?> mr-1"></i>
                                    <?php endif; ?>
                                    <?php echo e($item['title']); ?>

                                </span>
                            <?php endif; ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ol>
        </div>
    </nav>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\partials\breadcrumb.blade.php ENDPATH**/ ?>