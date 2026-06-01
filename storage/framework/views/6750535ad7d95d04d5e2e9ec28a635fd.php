<?php $__env->startSection('title', 'Certificate Verification - Panchi Gallery'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-white">
    <?php if($certificate): ?>
        <div class="max-w-4xl mx-auto px-4 py-12">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-light text-black mb-4">Certificate of Authenticity</h1>
                <p class="text-gray-600">Verify the authenticity of this artwork</p>
            </div>

            <!-- Certificate Card -->
            <div class="bg-white border border-black rounded-lg shadow-lg p-8 mb-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Artwork Information -->
                    <div>
                        <h2 class="text-2xl font-light mb-6">Artwork Details</h2>
                        
                        <?php if($certificate->artwork->images): ?>
                            <div class="mb-6">
                                <img src="<?php echo e($certificate->artwork->primary_image); ?>" 
                                     alt="<?php echo e($certificate->artwork->title); ?>" 
                                     class="w-full h-64 object-cover rounded-lg">
                            </div>
                        <?php endif; ?>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="font-medium">Title:</span>
                                <span><?php echo e($certificate->artwork->title); ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="font-medium">Artist:</span>
                                <span><?php echo e($certificate->artwork->artist->name); ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="font-medium">Medium:</span>
                                <span class="capitalize"><?php echo e($certificate->artwork->medium); ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="font-medium">Dimensions:</span>
                                <span><?php echo e($certificate->artwork->dimensions); ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="font-medium">Year:</span>
                                <span><?php echo e($certificate->artwork->year ?? 'N/A'); ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="font-medium">Category:</span>
                                <span><?php echo e($certificate->artwork->category->name); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate Information -->
                    <div>
                        <h2 class="text-2xl font-light mb-6">Certificate Information</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="font-medium">Certificate Code:</span>
                                <span class="font-mono"><?php echo e($certificate->certificate_code); ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="font-medium">Issue Date:</span>
                                <span><?php echo e($certificate->issue_date->format('F j, Y')); ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="font-medium">Status:</span>
                                <?php $isVerified = $certificate->is_verified; ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e($isVerified ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo e($isVerified ? 'Verified' : 'Not Verified'); ?>

                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="font-medium">Current Owner:</span>
                                <span><?php echo e($certificate->current_owner?->name ?? 'N/A'); ?></span>
                            </div>
                        </div>

                        <!-- QR Code -->
                        <?php if($certificate->qr_code): ?>
                            <div class="mt-6 text-center">
                                <p class="text-sm text-gray-600 mb-3">Scan QR code to verify</p>
                                <img src="<?php echo e($certificate->qr_code_url); ?>" 
                                     alt="Verification QR Code" 
                                     class="mx-auto w-32 h-32 border border-gray-300">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Certificate Text -->
                <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-light mb-4">Certificate Text</h3>
                    <p class="whitespace-pre-line text-gray-700"><?php echo e($certificate->certificate_text); ?></p>
                </div>

                <!-- Ownership History -->
                <?php if($certificate->ownership_history): ?>
                    <div class="mt-8">
                        <h3 class="text-2xl font-light mb-6">Ownership History</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr class="border-b-2 border-black">
                                        <th class="text-left py-3 px-4">Owner</th>
                                        <th class="text-left py-3 px-4">Acquired Date</th>
                                        <th class="text-left py-3 px-4">Purchase Price</th>
                                        <th class="text-left py-3 px-4">Transaction Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $certificate->ownership_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ownership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="border-b border-gray-200">
                                            <td class="py-3 px-4"><?php echo e($ownership['owner']['name']); ?></td>
                                            <td class="py-3 px-4"><?php echo e(\Carbon\Carbon::parse($ownership['acquired_at'])->format('M j, Y')); ?></td>
                                            <td class="py-3 px-4"><?php echo e($ownership['purchase_price'] ? '$' . number_format($ownership['purchase_price'], 2) : 'N/A'); ?></td>
                                            <td class="py-3 px-4"><?php echo e(ucfirst($ownership['transaction_type'])); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Actions -->
                <div class="mt-8 flex flex-wrap gap-4">
                    <?php if($certificate->certificate_pdf): ?>
                        <a href="<?php echo e($certificate->certificate_pdf_url); ?>" 
                           download="certificate-<?php echo e($certificate->certificate_code); ?>.pdf"
                           class="inline-flex items-center px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Certificate
                        </a>
                    <?php endif; ?>
                    
                    <button onclick="window.print()" 
                            class="inline-flex items-center px-6 py-3 border border-black text-black rounded-lg hover:bg-gray-100 transition-colors"
                            aria-label="Print certificate">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                            Print Certificate
                    </button>
                </div>
            </div>

            <!-- Verification Badge -->
            <div class="text-center mt-8">
                <div class="inline-flex items-center px-6 py-3 bg-green-50 border border-green-200 rounded-full">
                    <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-green-800 font-medium">Verified by Panchi Gallery</span>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="max-w-2xl mx-auto px-4 py-12 text-center">
            <div class="bg-white border border-black rounded-lg shadow-lg p-8">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                
                <h1 class="text-3xl font-light text-black mb-4">Certificate Not Found</h1>
                <p class="text-gray-600 mb-8">
                    The certificate code you entered could not be found in our system. 
                    Please verify the code and try again.
                </p>
                
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <p class="font-mono text-lg"><?php echo e(request('certificate_code')); ?></p>
                </div>
                
                <div class="space-y-4">
                    <a href="<?php echo e(route('home')); ?>" 
                       class="inline-flex items-center px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Return to Home
                    </a>
                    
                    <button onclick="history.back()" 
                            class="block w-full md:inline-block md:w-auto px-6 py-3 border border-black text-black rounded-lg hover:bg-gray-100 transition-colors">
                        Go Back
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\verify\certificate.blade.php ENDPATH**/ ?>