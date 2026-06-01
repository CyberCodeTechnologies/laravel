

<?php $__env->startSection('title', 'Marketplace Activity Reports - Admin - Panchi Gallery'); ?>
<?php $__env->startSection('header', 'Marketplace Activity Analytics'); ?>

<?php $__env->startSection('admin_content'); ?>
<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filters and Controls -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Marketplace Activity Analytics</h2>
            <div class="flex gap-4 items-center">
                <select class="px-4 py-2 border border-gray-300 rounded-lg" id="periodFilter">
                    <option value="day" <?php echo e($period === 'day' ? 'selected' : ''); ?>>Today</option>
                    <option value="week" <?php echo e($period === 'week' ? 'selected' : ''); ?>>This Week</option>
                    <option value="month" <?php echo e($period === 'month' ? 'selected' : ''); ?>>This Month</option>
                    <option value="quarter" <?php echo e($period === 'quarter' ? 'selected' : ''); ?>>This Quarter</option>
                    <option value="year" <?php echo e($period === 'year' ? 'selected' : ''); ?>>This Year</option>
                </select>
                <a href="<?php echo e(route('admin.reports.export', 'marketplace')); ?>?period=<?php echo e($period); ?>" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </a>
            </div>
        </div>

        <!-- Marketplace Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100">Total Listings</p>
                        <p class="text-3xl font-bold"><?php echo e($totalListings); ?></p>
                        <p class="text-sm mt-2">All time</p>
                    </div>
                    <div class="text-4xl opacity-80">📋</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100">Active Listings</p>
                        <p class="text-3xl font-bold"><?php echo e($activeListings); ?></p>
                        <p class="text-sm mt-2">Currently for sale</p>
                    </div>
                    <div class="text-4xl opacity-80">🏪</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100">Sold Listings</p>
                        <p class="text-3xl font-bold"><?php echo e($soldListings); ?></p>
                        <p class="text-sm mt-2">Completed sales</p>
                    </div>
                    <div class="text-4xl opacity-80">✅</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100">Pending Listings</p>
                        <p class="text-3xl font-bold"><?php echo e($pendingListings); ?></p>
                        <p class="text-sm mt-2">Awaiting approval</p>
                    </div>
                    <div class="text-4xl opacity-80">⏳</div>
                </div>
            </div>
        </div>

        <!-- Resale Listing Trends -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Resale Listing Trends</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <canvas id="listingTrendsChart"></canvas>
            </div>
        </div>

        <!-- Resales by Status -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Resales by Status</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php $__currentLoopData = $resalesByStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="text-center p-4 bg-<?php echo e($status->status === 'listed' ? 'green' : 
                        ($status->status === 'sold' ? 'blue' : 
                        ($status->status === 'pending' ? 'yellow' : 'gray'))); ?>-50 rounded-lg border border-<?php echo e($status->status === 'listed' ? 'green' : 
                        ($status->status === 'sold' ? 'blue' : 
                        ($status->status === 'pending' ? 'yellow' : 'gray'))); ?>-200">
                        <p class="text-2xl font-bold text-<?php echo e($status->status === 'listed' ? 'green' : 
                            ($status->status === 'sold' ? 'blue' : 
                            ($status->status === 'pending' ? 'yellow' : 'gray'))); ?>-600"><?php echo e($status->count); ?></p>
                        <p class="text-sm text-gray-600 mt-1"><?php echo e(ucfirst($status->status)); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Price Analysis -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Price Analysis</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Average Listing Price</span>
                        <span class="text-lg font-bold text-gray-900">$<?php echo e(number_format($avgListingPrice, 2)); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Average Sold Price</span>
                        <span class="text-lg font-bold text-green-600">$<?php echo e(number_format($avgSoldPrice, 2)); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Price Difference</span>
                        <span class="text-lg font-bold <?php echo e($avgSoldPrice > $avgListingPrice ? 'text-green-600' : 'text-red-600'); ?>">
                            <?php echo e($avgSoldPrice > $avgListingPrice ? '+' : '-'); ?>$<?php echo e(number_format(abs($avgSoldPrice - $avgListingPrice), 2)); ?>

                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Average Time to Sell</span>
                        <span class="text-lg font-bold text-blue-600"><?php echo e($timeToSell ? round($timeToSell) : 'N/A'); ?> days</span>
                    </div>
                </div>
            </div>

            <!-- Success Rate -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Marketplace Performance</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-gray-600">Sell-through Rate</span>
                            <span class="text-sm font-medium"><?php echo e($totalListings > 0 ? number_format(($soldListings / $totalListings) * 100, 1) : 0); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: <?php echo e($totalListings > 0 ? ($soldListings / $totalListings) * 100 : 0); ?>%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-gray-600">Active Rate</span>
                            <span class="text-sm font-medium"><?php echo e($totalListings > 0 ? number_format(($activeListings / $totalListings) * 100, 1) : 0); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e($totalListings > 0 ? ($activeListings / $totalListings) * 100 : 0); ?>%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-gray-600">Pending Rate</span>
                            <span class="text-sm font-medium"><?php echo e($totalListings > 0 ? number_format(($pendingListings / $totalListings) * 100, 1) : 0); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: <?php echo e($totalListings > 0 ? ($pendingListings / $totalListings) * 100 : 0); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Reselling Artists -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Reselling Artists</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Artist</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Total Listings</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Sold</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Success Rate</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $topResellingArtists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-300 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900"><?php echo e($artist->name); ?></p>
                                            <p class="text-xs text-gray-500">Artist</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 text-sm"><?php echo e($artist->listings); ?></td>
                                <td class="py-3 text-sm"><?php echo e($artist->sold); ?></td>
                                <td class="py-3 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        <?php echo e(($artist->sold / $artist->listings) * 100 >= 50 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                        <?php echo e(number_format(($artist->sold / $artist->listings) * 100, 1)); ?>%
                                    </span>
                                </td>
                                <td class="py-3">
                                    <a href="<?php echo e(route('admin.artists.edit', $artist->id)); ?>" class="text-blue-600 hover:underline text-sm">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Most Resold Artworks -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Most Resold Artworks</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php $__currentLoopData = $mostResoldArtworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artwork): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-gray-900 text-sm"><?php echo e($artwork->title); ?></h4>
                            <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">
                                <?php echo e($artwork->resale_count); ?>x
                            </span>
                        </div>
                        <p class="text-xs text-gray-500">Resold <?php echo e($artwork->resale_count); ?> times</p>
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-1">
                                <div class="bg-purple-600 h-1 rounded-full" style="width: <?php echo e(($artwork->resale_count / $mostResoldArtworks->first()->resale_count) * 100); ?>%"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Marketplace Metrics Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">Market Activity</h3>
                <p class="text-3xl font-bold text-blue-900"><?php echo e($activeListings); ?></p>
                <p class="text-sm text-blue-700 mt-2">Currently active listings</p>
            </div>
            <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-green-800 mb-2">Total Sales</h3>
                <p class="text-3xl font-bold text-green-900"><?php echo e($soldListings); ?></p>
                <p class="text-sm text-green-700 mt-2">Resales completed</p>
            </div>
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-purple-800 mb-2">Avg Time to Sell</h3>
                <p class="text-3xl font-bold text-purple-900"><?php echo e($timeToSell ? round($timeToSell) : 'N/A'); ?></p>
                <p class="text-sm text-purple-700 mt-2">Days on market</p>
            </div>
        </div>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('listingTrendsChart').getContext('2d');
    
    const listingData = <?php echo json_encode($resaleListings->map(function($item) {
        return [
            $item->date, $item->listings
        ];
    }), 512) ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: listingData.map(item => item[0]),
            datasets: [{
                label: 'New Listings',
                data: listingData.map(item => item[1]),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1
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
        window.location.href = `<?php echo e(route('admin.reports.marketplace')); ?>?period=` + period;
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\reports\marketplace.blade.php ENDPATH**/ ?>