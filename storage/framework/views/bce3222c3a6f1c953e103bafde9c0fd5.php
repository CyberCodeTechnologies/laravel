<!-- Flash Messages -->
<?php if(session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info')): ?>
    <div class="fixed top-20 right-4 z-50 space-y-2 max-w-sm">
        <?php if(session()->has('success')): ?>
            <div data-flash-message class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-lg fade-in flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-600"></i>
                <span><?php echo e(session('success')); ?></span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-green-600 hover:text-green-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>
        
        <?php if(session()->has('error')): ?>
            <div data-flash-message class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-lg fade-in flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-600"></i>
                <span><?php echo e(session('error')); ?></span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>
        
        <?php if(session()->has('warning')): ?>
            <div data-flash-message class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg shadow-lg fade-in flex items-center">
                <i class="fas fa-exclamation-triangle mr-3 text-yellow-600"></i>
                <span><?php echo e(session('warning')); ?></span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-yellow-600 hover:text-yellow-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>
        
        <?php if(session()->has('info')): ?>
            <div data-flash-message class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg shadow-lg fade-in flex items-center">
                <i class="fas fa-info-circle mr-3 text-blue-600"></i>
                <span><?php echo e(session('info')); ?></span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-blue-600 hover:text-blue-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Validation Errors -->
<?php if($errors->any()): ?>
    <div class="fixed top-20 right-4 z-50 max-w-sm">
        <div data-flash-message class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-lg fade-in">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle mr-3 text-red-600 mt-1"></i>
                <div class="flex-1">
                    <p class="font-semibold">Please fix the following errors:</p>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\partials\flash-messages.blade.php ENDPATH**/ ?>