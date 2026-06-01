

<?php $__env->startSection('title', 'Financial Summary Reports - Admin - Panchi Gallery'); ?>
<?php $__env->startSection('header', 'Financial Summary Analytics'); ?>

<?php $__env->startSection('admin_content'); ?>
<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filters and Controls -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Financial Summary Analytics</h2>
            <div class="flex gap-4 items-center">
                <select class="px-4 py-2 border border-gray-300 rounded-lg" id="periodFilter">
                    <option value="day" <?php echo e($period === 'day' ? 'selected' : ''); ?>>Today</option>
                    <option value="week" <?php echo e($period === 'week' ? 'selected' : ''); ?>>This Week</option>
                    <option value="month" <?php echo e($period === 'month' ? 'selected' : ''); ?>>This Month</option>
                    <option value="quarter" <?php echo e($period === 'quarter' ? 'selected' : ''); ?>>This Quarter</option>
                    <option value="year" <?php echo e($period === 'year' ? 'selected' : ''); ?>>This Year</option>
                </select>
                <a href="<?php echo e(route('admin.reports.export', 'financial')); ?>?period=<?php echo e($period); ?>" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </a>
            </div>
        </div>

        <!-- Financial Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100">Total Revenue</p>
                        <p class="text-3xl font-bold">$<?php echo e(number_format($totalRevenue, 2)); ?></p>
                        <p class="text-sm mt-2">This period</p>
                    </div>
                    <div class="text-4xl opacity-80">💰</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100">Platform Fees</p>
                        <p class="text-3xl font-bold">$<?php echo e(number_format($platformFees, 2)); ?></p>
                        <p class="text-sm mt-2"><?php echo e($totalRevenue > 0 ? number_format(($platformFees / $totalRevenue) * 100, 1) : 0); ?>% of revenue</p>
                    </div>
                    <div class="text-4xl opacity-80">📊</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100">Artist Earnings</p>
                        <p class="text-3xl font-bold">$<?php echo e(number_format($artistEarnings, 2)); ?></p>
                        <p class="text-sm mt-2">Paid to artists</p>
                    </div>
                    <div class="text-4xl opacity-80">🎨</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100">Profit Margin</p>
                        <p class="text-3xl font-bold"><?php echo e(number_format($profitMargin, 1)); ?>%</p>
                        <p class="text-sm mt-2">Platform profit</p>
                    </div>
                    <div class="text-4xl opacity-80">📈</div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Revenue Trends</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Revenue Breakdown -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Revenue by Transaction Type -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue by Transaction Type</h3>
                <div class="space-y-3">
                    <?php $__currentLoopData = $revenueByType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700"><?php echo e(ucfirst(str_replace('_', ' ', $type->type))); ?></span>
                                    <span class="text-sm text-gray-600">$<?php echo e(number_format($type->revenue, 2)); ?></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-<?php echo e($type->type === 'primary_sale' ? 'green' : 'blue'); ?>-600 h-2 rounded-full" style="width: <?php echo e(($type->revenue / $revenueByType->sum('revenue')) * 100); ?>%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1"><?php echo e($type->count); ?> transactions</p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Revenue by Currency -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue by Currency</h3>
                <div class="space-y-3">
                    <?php $__currentLoopData = $revenueByCurrency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700"><?php echo e($currency->currency); ?></span>
                                    <span class="text-sm text-gray-600"><?php echo e($currency->currency === 'USD' ? '$' : 'K'); ?><?php echo e(number_format($currency->revenue, $currency->currency === 'USD' ? 2 : 0)); ?></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: <?php echo e(($currency->revenue / $revenueByCurrency->sum('revenue')) * 100); ?>%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1"><?php echo e($currency->count); ?> transactions</p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Payout Status -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payout Status</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg border border-green-200">
                        <div>
                            <p class="text-sm font-medium text-green-800">Completed Payouts</p>
                            <p class="text-2xl font-bold text-green-900">$<?php echo e(number_format($totalPayouts, 2)); ?></p>
                        </div>
                        <div class="text-3xl text-green-600">✅</div>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Pending Payouts</p>
                            <p class="text-2xl font-bold text-yellow-900">$<?php echo e(number_format($pendingPayouts, 2)); ?></p>
                        </div>
                        <div class="text-3xl text-yellow-600">⏳</div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-600">Payout Completion Rate</span>
                            <span class="text-sm font-medium"><?php echo e(($totalPayouts + $pendingPayouts) > 0 ? number_format(($totalPayouts / ($totalPayouts + $pendingPayouts)) * 100, 1) : 0); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: <?php echo e(($totalPayouts + $pendingPayouts) > 0 ? ($totalPayouts / ($totalPayouts + $pendingPayouts)) * 100 : 0); ?>%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-600">Revenue Payout Ratio</span>
                            <span class="text-sm font-medium"><?php echo e($totalRevenue > 0 ? number_format(($totalPayouts / $totalRevenue) * 100, 1) : 0); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e($totalRevenue > 0 ? ($totalPayouts / $totalRevenue) * 100 : 0); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-green-800 mb-2">Gross Revenue</h3>
                <p class="text-3xl font-bold text-green-900">$<?php echo e(number_format($totalRevenue, 2)); ?></p>
                <p class="text-sm text-green-700 mt-2">Before fees</p>
            </div>
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">Net Revenue</h3>
                <p class="text-3xl font-bold text-blue-900">$<?php echo e(number_format($platformFees, 2)); ?></p>
                <p class="text-sm text-blue-700 mt-2">Platform earnings</p>
            </div>
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-purple-800 mb-2">Avg Transaction</h3>
                <p class="text-3xl font-bold text-purple-900">$<?php echo e(number_format($avgTransactionValue, 2)); ?></p>
                <p class="text-sm text-purple-700 mt-2">Per transaction</p>
            </div>
        </div>

        <!-- Revenue Breakdown Chart -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Distribution</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h4 class="text-md font-medium text-gray-700 mb-3">Revenue Share</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-sm text-gray-600">Platform Fees</span>
                            </div>
                            <span class="text-sm font-medium">$<?php echo e(number_format($platformFees, 2)); ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                <span class="text-sm text-gray-600">Artist Earnings</span>
                            </div>
                            <span class="text-sm font-medium">$<?php echo e(number_format($artistEarnings, 2)); ?></span>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-md font-medium text-gray-700 mb-3">Key Metrics</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Profit Margin</span>
                            <span class="font-medium"><?php echo e(number_format($profitMargin, 1)); ?>%</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Transactions</span>
                            <span class="font-medium"><?php echo e($revenueByType->sum('count')); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Avg Transaction Value</span>
                            <span class="font-medium">$<?php echo e(number_format($avgTransactionValue, 2)); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    <?php 
    $monthlyData = $monthlyRevenue->map(function($item) {
        return [
            $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT),
            $item->revenue
        ];
    });
?>
const monthlyData = <?php echo json_encode($monthlyData, 15, 512) ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item[0]),
            datasets: [{
                label: 'Monthly Revenue',
                data: monthlyData.map(item => item[1]),
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.1,
                fill: true
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
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Period filter change handler
    document.getElementById('periodFilter').addEventListener('change', function() {
        const period = this.value;
        window.location.href = `<?php echo e(route('admin.reports.financial')); ?>?period=` + period;
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\reports\financial.blade.php ENDPATH**/ ?>