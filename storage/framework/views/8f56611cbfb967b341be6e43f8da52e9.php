


<?php if($items->hasPages()): ?>
    <div class="flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Showing 
            <span class="font-medium"><?php echo e($items->firstItem()); ?></span>
            to 
            <span class="font-medium"><?php echo e($items->lastItem()); ?></span>
            of 
            <span class="font-medium"><?php echo e($items->total()); ?></span>
            results
        </div>
        
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            
            <?php if($items->onFirstPage()): ?>
                <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-500 cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </span>
            <?php else: ?>
                <a href="<?php echo e($items->previousPageUrl()); ?>" 
                   class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <i class="fas fa-chevron-left"></i>
                </a>
            <?php endif; ?>
            
            
            <?php $__currentLoopData = $elements = $items->links(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <?php if(is_string($element)): ?>
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                        <?php echo e($element); ?>

                    </span>
                <?php endif; ?>
                
                
                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $items->currentPage()): ?>
                            <span aria-current="page" 
                                  class="relative inline-flex items-center px-4 py-2 border border-indigo-500 bg-indigo-50 text-sm font-medium text-indigo-600">
                                <?php echo e($page); ?>

                            </span>
                        <?php else: ?>
                            <a href="<?php echo e($url); ?>" 
                               class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <?php echo e($page); ?>

                            </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            
            <?php if($items->hasMorePages()): ?>
                <a href="<?php echo e($items->nextPageUrl()); ?>" 
                   class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </a>
            <?php else: ?>
                <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-500 cursor-not-allowed">
                    <i class="fas fa-chevron-right"></i>
                </span>
            <?php endif; ?>
        </nav>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\partials\pagination.blade.php ENDPATH**/ ?>