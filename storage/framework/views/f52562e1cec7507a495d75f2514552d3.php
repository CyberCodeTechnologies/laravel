


<div class="flex items-center justify-center <?php echo e($class ?? ''); ?>">
    <div class="animate-spin rounded-full 
        <?php if($size === 'small'): ?>
            h-4 w-4 border-2
        <?php elseif($size === 'large'): ?>
            h-12 w-12 border-4
        <?php else: ?>
            h-8 w-8 border-3
        <?php endif; ?>
        
        border-<?php echo e($color ?? 'gray'); ?>-200 
        border-t-<?php echo e($color ?? 'indigo'); ?>-600">
    </div>
    <?php if(isset($text)): ?>
        <span class="ml-3 text-<?php echo e($color ?? 'gray'); ?>-600 <?php echo e($size === 'small' ? 'text-sm' : ''); ?>">
            <?php echo e($text); ?>

        </span>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\partials\loading-spinner.blade.php ENDPATH**/ ?>