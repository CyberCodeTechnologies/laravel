

<?php $__env->startSection('title', 'Customer Analytics Reports - Admin - Panchi Gallery'); ?>
<?php $__env->startSection('header', 'Customer Analytics'); ?>

<?php $__env->startSection('admin_content'); ?>
<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filters and Controls -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Customer Analytics</h2>
            <div class="flex gap-4 items-center">
                <select class="px-4 py-2 border border-gray-300 rounded-lg" id="periodFilter">
                    <option value="day" <?php echo e($period === 'day' ? 'selected' : ''); ?>>Today</option>
                    <option value="week" <?php echo e($period === 'week' ? 'selected' : ''); ?>>This Week</option>
                    <option value="month" <?php echo e($period === 'month' ? 'selected' : ''); ?>>This Month</option>
                    <option value="quarter" <?php echo e($period === 'quarter' ? 'selected' : ''); ?>>This Quarter</option>
                    <option value="year" <?php echo e($period === 'year' ? 'selected' : ''); ?>>This Year</option>
                </select>
                <a href="<?php echo e(route('admin.reports.export', 'customers')); ?>?period=<?php echo e($period); ?>" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </a>
            </div>
        </div>

        <!-- Customer Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100">New Customers</p>
                        <p class="text-3xl font-bold"><?php echo e($customerSegments['new_customers']); ?></p>
                        <p class="text-sm mt-2">This period</p>
                    </div>
                    <div class="text-4xl opacity-80">👤</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100">Returning Customers</p>
                        <p class="text-3xl font-bold"><?php echo e($customerSegments['returning_customers']); ?></p>
                        <p class="text-sm mt-2">Active buyers</p>
                    </div>
                    <div class="text-4xl opacity-80">🔄</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100">VIP Customers</p>
                        <p class="text-3xl font-bold"><?php echo e($customerSegments['vip_customers']); ?></p>
                        <p class="text-sm mt-2">5+ purchases</p>
                    </div>
                    <div class="text-4xl opacity-80">⭐</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100">Avg CLV</p>
                        <p class="text-3xl font-bold">$<?php echo e(number_format($customerLifetimeValue, 2)); ?></p>
                        <p class="text-sm mt-2">Lifetime value</p>
                    </div>
                    <div class="text-4xl opacity-80">💰</div>
                </div>
            </div>
        </div>

        <!-- Customer Acquisition Chart -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Acquisition Trends</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <canvas id="acquisitionChart"></canvas>
            </div>
        </div>

        <!-- Customer Segments -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Segments</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-green-50 rounded-lg border border-green-200">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl">👤</span>
                    </div>
                    <h4 class="text-lg font-semibold text-green-800 mb-2">New Customers</h4>
                    <p class="text-3xl font-bold text-green-900 mb-2"><?php echo e($customerSegments['new_customers']); ?></p>
                    <p class="text-sm text-green-700">First-time buyers this period</p>
                </div>

                <div class="text-center p-6 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl">🔄</span>
                    </div>
                    <h4 class="text-lg font-semibold text-blue-800 mb-2">Returning Customers</h4>
                    <p class="text-3xl font-bold text-blue-900 mb-2"><?php echo e($customerSegments['returning_customers']); ?></p>
                    <p class="text-sm text-blue-700">Repeat buyers</p>
                </div>

                <div class="text-center p-6 bg-purple-50 rounded-lg border border-purple-200">
                    <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl">⭐</span>
                    </div>
                    <h4 class="text-lg font-semibold text-purple-800 mb-2">VIP Customers</h4>
                    <p class="text-3xl font-bold text-purple-900 mb-2"><?php echo e($customerSegments['vip_customers']); ?></p>
                    <p class="text-sm text-purple-700">5+ purchases</p>
                </div>
            </div>
        </div>

        <!-- Top Customers by Spending -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Customers by Spending</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Customer</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Total Spent</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Purchases</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Avg Purchase</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $topCustomersBySpending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-300 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900"><?php echo e($customer->name); ?></p>
                                            <p class="text-xs text-gray-500">Customer ID: <?php echo e($customer->id); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 text-sm font-medium">$<?php echo e(number_format($customer->total_spent, 2)); ?></td>
                                <td class="py-3 text-sm"><?php echo e($customer->purchases); ?></td>
                                <td class="py-3 text-sm">$<?php echo e(number_format($customer->total_spent / $customer->purchases, 2)); ?></td>
                                <td class="py-3">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        <?php echo e($customer->purchases >= 5 ? 'bg-purple-100 text-purple-800' : 
                                           ($customer->purchases >= 2 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')); ?>">
                                        <?php echo e($customer->purchases >= 5 ? 'VIP' : 
                                           ($customer->purchases >= 2 ? 'Regular' : 'New')); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Customer Behavior Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Purchase Frequency -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Purchase Behavior</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-gray-600">Average Purchase Frequency</span>
                            <span class="text-sm font-medium"><?php echo e(number_format($avgPurchaseFrequency, 1)); ?> purchases</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e(min(($avgPurchaseFrequency / 10) * 100, 100)); ?>%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-gray-600">Cart Abandonment Rate</span>
                            <span class="text-sm font-medium"><?php echo e(number_format($cartAbandonmentRate, 1)); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-600 h-2 rounded-full" style="width: <?php echo e($cartAbandonmentRate); ?>%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-gray-600">Wishlist Activity</span>
                            <span class="text-sm font-medium"><?php echo e($wishlistToPurchaseRate); ?> items</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: <?php echo e(min(($wishlistToPurchaseRate / 100) * 100, 100)); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Value Distribution -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Value Distribution</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">High Value (> $1000)</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="bg-green-600 h-2 rounded-full" style="width: 25%"></div>
                            </div>
                            <span class="text-sm text-gray-600">25%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Medium ($100-$1000)</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 45%"></div>
                            </div>
                            <span class="text-sm text-gray-600">45%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Low (< $100)</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="bg-yellow-600 h-2 rounded-full" style="width: 30%"></div>
                            </div>
                            <span class="text-sm text-gray-600">30%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Retention Metrics -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Retention Analysis</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-900 mb-2">68%</p>
                    <p class="text-sm text-gray-600">Repeat Purchase Rate</p>
                    <p class="text-xs text-gray-500 mt-1">Customers who buy again</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-900 mb-2">45 days</p>
                    <p class="text-sm text-gray-600">Average Purchase Interval</p>
                    <p class="text-xs text-gray-500 mt-1">Time between purchases</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-900 mb-2">3.2x</p>
                    <p class="text-sm text-gray-600">LTV to CAC Ratio</p>
                    <p class="text-xs text-gray-500 mt-1">Return on acquisition</p>
                </div>
            </div>
        </div>

        <!-- Customer Journey Funnel -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Journey Funnel</h3>
            <div class="space-y-3">
                <div class="flex items-center">
                    <div class="w-32 text-sm text-gray-600">Visitors</div>
                    <div class="flex-1 mx-4">
                        <div class="w-full bg-gray-200 rounded-full h-8">
                            <div class="bg-blue-600 h-8 rounded-full flex items-center justify-center text-white text-xs font-medium" style="width: 100%">
                                100%
                            </div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">10,000</div>
                </div>
                <div class="flex items-center">
                    <div class="w-32 text-sm text-gray-600">Sign-ups</div>
                    <div class="flex-1 mx-4">
                        <div class="w-full bg-gray-200 rounded-full h-8">
                            <div class="bg-blue-500 h-8 rounded-full flex items-center justify-center text-white text-xs font-medium" style="width: 25%">
                                25%
                            </div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">2,500</div>
                </div>
                <div class="flex items-center">
                    <div class="w-32 text-sm text-gray-600">First Purchase</div>
                    <div class="flex-1 mx-4">
                        <div class="w-full bg-gray-200 rounded-full h-8">
                            <div class="bg-blue-400 h-8 rounded-full flex items-center justify-center text-white text-xs font-medium" style="width: 15%">
                                15%
                            </div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">1,500</div>
                </div>
                <div class="flex items-center">
                    <div class="w-32 text-sm text-gray-600">Repeat Purchase</div>
                    <div class="flex-1 mx-4">
                        <div class="w-full bg-gray-200 rounded-full h-8">
                            <div class="bg-green-500 h-8 rounded-full flex items-center justify-center text-white text-xs font-medium" style="width: 10%">
                                10%
                            </div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">1,000</div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('acquisitionChart').getContext('2d');
    
    const acquisitionData = <?php echo json_encode($customerAcquisition->map(function($item) {
        return [
            $item->date, $item->customers
        ];
    }), 512) ?>;
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: acquisitionData.map(item => item[0]),
            datasets: [{
                label: 'New Customers',
                data: acquisitionData.map(item => item[1]),
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgb(34, 197, 94)',
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
        window.location.href = `<?php echo e(route('admin.reports.customers')); ?>?period=` + period;
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\reports\customers.blade.php ENDPATH**/ ?>