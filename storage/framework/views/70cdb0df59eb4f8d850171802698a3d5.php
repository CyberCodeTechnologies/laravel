

<?php $__env->startSection('title', 'Artworks & Inventory Reports - Admin - Panchi Gallery'); ?>
<?php $__env->startSection('header', 'Artworks & Inventory Analytics'); ?>

<?php $__env->startSection('admin_content'); ?>
<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filters and Controls -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Artworks & Inventory Analytics</h2>
            <div class="flex gap-4 items-center">
                <select class="px-4 py-2 border border-gray-300 rounded-lg" id="periodFilter">
                    <option value="day" <?php echo e($period === 'day' ? 'selected' : ''); ?>>Today</option>
                    <option value="week" <?php echo e($period === 'week' ? 'selected' : ''); ?>>This Week</option>
                    <option value="month" <?php echo e($period === 'month' ? 'selected' : ''); ?>>This Month</option>
                    <option value="quarter" <?php echo e($period === 'quarter' ? 'selected' : ''); ?>>This Quarter</option>
                    <option value="year" <?php echo e($period === 'year' ? 'selected' : ''); ?>>This Year</option>
                </select>
                <a href="<?php echo e(route('admin.reports.export', 'artworks')); ?>?period=<?php echo e($period); ?>" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </a>
            </div>
        </div>

        <!-- Artwork Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100">Total Artworks</p>
                        <p class="text-3xl font-bold"><?php echo e($totalArtworks); ?></p>
                        <p class="text-sm mt-2">All artworks</p>
                    </div>
                    <div class="text-4xl opacity-80">🎨</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100">Approved</p>
                        <p class="text-3xl font-bold"><?php echo e($approvedArtworks); ?></p>
                        <p class="text-sm mt-2">Live on platform</p>
                    </div>
                    <div class="text-4xl opacity-80">✅</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100">Pending</p>
                        <p class="text-3xl font-bold"><?php echo e($pendingArtworks); ?></p>
                        <p class="text-sm mt-2">Awaiting review</p>
                    </div>
                    <div class="text-4xl opacity-80">⏳</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100">Sold</p>
                        <p class="text-3xl font-bold"><?php echo e($soldArtworks); ?></p>
                        <p class="text-sm mt-2">Completed sales</p>
                    </div>
                    <div class="text-4xl opacity-80">💰</div>
                </div>
            </div>
        </div>

        <!-- Artwork Creation Chart -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Artwork Creation Trends</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <canvas id="creationChart"></canvas>
            </div>
        </div>

        <!-- Artwork Status and Categories -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Artworks by Status -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Artworks by Status</h3>
                <div class="space-y-3">
                    <?php $__currentLoopData = $artworksByStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700"><?php echo e(ucfirst($status->status)); ?></span>
                                    <span class="text-sm text-gray-600"><?php echo e($status->count); ?></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-<?php echo e($status->status === 'approved' ? 'green' : ($status->status === 'pending' ? 'yellow' : ($status->status === 'sold' ? 'red' : 'gray'))); ?>-600 h-2 rounded-full" style="width: <?php echo e(($status->count / $artworksByStatus->sum('count')) * 100); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Artworks by Category -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Artworks by Category</h3>
                <div class="space-y-3">
                    <?php $__currentLoopData = $artworksByCategory->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700"><?php echo e($category->name); ?></span>
                                    <span class="text-sm text-gray-600"><?php echo e($category->count); ?></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e(($category->count / $artworksByCategory->sum('count')) * 100); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Most Viewed Artworks -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Most Viewed Artworks</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php $__currentLoopData = $mostViewedArtworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artwork): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                        <div class="flex items-start space-x-3">
                            <img src="<?php echo e($artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>" 
                                 alt="<?php echo e($artwork->title); ?>" 
                                 class="w-16 h-16 object-cover rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 text-sm"><?php echo e($artwork->title); ?></h4>
                                <p class="text-xs text-gray-500"><?php echo e($artwork->artist->name); ?></p>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-xs text-gray-600"><?php echo e($artwork->views_count); ?> views</span>
                                    <span class="text-xs font-medium text-green-600">$<?php echo e(number_format($artwork->price, 2)); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Most Liked Artworks -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Most Liked Artworks</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php $__currentLoopData = $mostLikedArtworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artwork): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                        <div class="flex items-start space-x-3">
                            <img src="<?php echo e($artwork->primary_image ?? asset('images/placeholder-artwork.jpg')); ?>" 
                                 alt="<?php echo e($artwork->title); ?>" 
                                 class="w-16 h-16 object-cover rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 text-sm"><?php echo e($artwork->title); ?></h4>
                                <p class="text-xs text-gray-500"><?php echo e($artwork->artist->name); ?></p>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-xs text-gray-600">❤️ <?php echo e($artwork->likes_count); ?> likes</span>
                                    <span class="text-xs font-medium text-green-600">$<?php echo e(number_format($artwork->price, 2)); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Price Distribution -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Price Distribution</h3>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <?php $__currentLoopData = $priceDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $range => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900"><?php echo e($count); ?></p>
                        <p class="text-sm text-gray-600">$<?php echo e($range); ?></p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e(($count / array_sum($priceDistribution)) * 100); ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Medium Distribution -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Medium Distribution</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php $__currentLoopData = $mediumDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medium): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700"><?php echo e($medium->medium); ?></span>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 mr-2"><?php echo e($medium->count); ?></span>
                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: <?php echo e(($medium->count / $mediumDistribution->sum('count')) * 100); ?>%"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Inventory Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">Total Inventory Value</h3>
                <p class="text-3xl font-bold text-blue-900">$<?php echo e(number_format($totalInventoryValue, 2)); ?></p>
                <p class="text-sm text-blue-700 mt-2">All approved artworks</p>
            </div>
            <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-green-800 mb-2">Average Price</h3>
                <p class="text-3xl font-bold text-green-900">$<?php echo e(number_format($avgPrice, 2)); ?></p>
                <p class="text-sm text-green-700 mt-2">Per artwork</p>
            </div>
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-purple-800 mb-2">Approval Rate</h3>
                <p class="text-3xl font-bold text-purple-900"><?php echo e($totalArtworks > 0 ? number_format(($approvedArtworks / $totalArtworks) * 100, 1) : 0); ?>%</p>
                <p class="text-sm text-purple-700 mt-2">Artworks approved</p>
            </div>
        </div>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('creationChart').getContext('2d');
    
    const creationData = <?php echo json_encode($artworkCreations->map(function($item) {
        return [
            $item->date, $item->artworks
        ];
    }), 512) ?>;
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: creationData.map(item => item[0]),
            datasets: [{
                label: 'New Artworks',
                data: creationData.map(item => item[1]),
                backgroundColor: 'rgba(147, 51, 234, 0.8)',
                borderColor: 'rgb(147, 51, 234)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Period filter change handler
    document.getElementById('periodFilter').addEventListener('change', function() {
        const period = this.value;
        window.location.href = `<?php echo e(route('admin.reports.artworks')); ?>?period=` + period;
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\reports\artworks.blade.php ENDPATH**/ ?>