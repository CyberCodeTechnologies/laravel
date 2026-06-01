

<?php $__env->startSection('title', 'Users & Artists Reports - Admin - Panchi Gallery'); ?>
<?php $__env->startSection('header', 'Users & Artists Analytics'); ?>

<?php $__env->startSection('admin_content'); ?>
<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filters and Controls -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Users & Artists Analytics</h2>
            <div class="flex gap-4 items-center">
                <select class="px-4 py-2 border border-gray-300 rounded-lg" id="periodFilter">
                    <option value="day" <?php echo e($period === 'day' ? 'selected' : ''); ?>>Today</option>
                    <option value="week" <?php echo e($period === 'week' ? 'selected' : ''); ?>>This Week</option>
                    <option value="month" <?php echo e($period === 'month' ? 'selected' : ''); ?>>This Month</option>
                    <option value="quarter" <?php echo e($period === 'quarter' ? 'selected' : ''); ?>>This Quarter</option>
                    <option value="year" <?php echo e($period === 'year' ? 'selected' : ''); ?>>This Year</option>
                </select>
                <a href="<?php echo e(route('admin.reports.export', 'users')); ?>?period=<?php echo e($period); ?>" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </a>
            </div>
        </div>

        <!-- User Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100">Total Users</p>
                        <p class="text-3xl font-bold"><?php echo e($totalArtists + $totalCollectors); ?></p>
                        <p class="text-sm mt-2">All registered users</p>
                    </div>
                    <div class="text-4xl opacity-80">👥</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100">Total Artists</p>
                        <p class="text-3xl font-bold"><?php echo e($totalArtists); ?></p>
                        <p class="text-sm mt-2"><?php echo e($approvedArtists); ?> approved</p>
                    </div>
                    <div class="text-4xl opacity-80">🎨</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100">Total Collectors</p>
                        <p class="text-3xl font-bold"><?php echo e($totalCollectors); ?></p>
                        <p class="text-sm mt-2">Active buyers</p>
                    </div>
                    <div class="text-4xl opacity-80">💎</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100">New This Period</p>
                        <p class="text-3xl font-bold"><?php echo e($newArtistsThisPeriod); ?></p>
                        <p class="text-sm mt-2">New artists joined</p>
                    </div>
                    <div class="text-4xl opacity-80">📈</div>
                </div>
            </div>
        </div>

        <!-- User Registration Chart -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">User Registration Trends</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <canvas id="registrationChart"></canvas>
            </div>
        </div>

        <!-- User Distribution -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Users by Role -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Users by Role</h3>
                <div class="space-y-3">
                    <?php $__currentLoopData = $usersByRole; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700"><?php echo e(ucfirst($role->role)); ?>s</span>
                                    <span class="text-sm text-gray-600"><?php echo e($role->count); ?></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e(($role->count / $usersByRole->sum('count')) * 100); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Artist Status -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Artist Approval Status</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-2xl font-bold text-green-600"><?php echo e($approvedArtists); ?></p>
                        <p class="text-sm text-gray-600">Approved</p>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <p class="text-2xl font-bold text-yellow-600"><?php echo e($pendingArtists); ?></p>
                        <p class="text-sm text-gray-600">Pending</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-gray-600"><?php echo e($totalArtists - $approvedArtists - $pendingArtists); ?></p>
                        <p class="text-sm text-gray-600">Other</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Artists by Sales -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Artists by Revenue</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Artist</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Sales</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Revenue</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Avg Sale</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $topArtistsBySales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-300 rounded-full mr-3"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900"><?php echo e($artist->name); ?></p>
                                            <p class="text-xs text-gray-500">Artist ID: <?php echo e($artist->id); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 text-sm"><?php echo e($artist->sales); ?></td>
                                <td class="py-3 text-sm font-medium">$<?php echo e(number_format($artist->revenue, 2)); ?></td>
                                <td class="py-3 text-sm">$<?php echo e(number_format($artist->revenue / $artist->sales, 2)); ?></td>
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

        <!-- Top Artists by Artwork Count -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Most Prolific Artists</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php $__currentLoopData = $topArtistsByArtworks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                        <div class="flex items-center mb-3">
                            <div class="w-12 h-12 bg-gray-300 rounded-full mr-3"></div>
                            <div>
                                <p class="font-medium text-gray-900"><?php echo e($artist->name); ?></p>
                                <p class="text-sm text-gray-500">Artist</p>
                            </div>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Artworks:</span>
                            <span class="font-medium"><?php echo e($artist->artworks_count); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- User Activity Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">User Activity</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Active Users</span>
                        <span class="font-medium text-green-600"><?php echo e($activeUsers); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Inactive Users</span>
                        <span class="font-medium text-red-600"><?php echo e($inactiveUsers); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Activity Rate</span>
                        <span class="font-medium"><?php echo e($activeUsers > 0 ? number_format(($activeUsers / ($activeUsers + $inactiveUsers)) * 100, 1) : 0); ?>%</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Artist Performance</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Avg Artworks/Artist</span>
                        <span class="font-medium"><?php echo e(number_format($avgArtworksPerArtist, 1)); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Approval Rate</span>
                        <span class="font-medium"><?php echo e($totalArtists > 0 ? number_format(($approvedArtists / $totalArtists) * 100, 1) : 0); ?>%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">New Artists/Month</span>
                        <span class="font-medium"><?php echo e($newArtistsThisPeriod); ?></span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Geographic Distribution</h3>
                <div class="space-y-2">
                    <?php $__currentLoopData = $usersByLocation->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600"><?php echo e($location->location); ?></span>
                            <span class="font-medium"><?php echo e($location->count); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('registrationChart').getContext('2d');
    
    const registrationData = <?php echo json_encode($userRegistrations->map(function($item) {
        return [
            $item->date, $item->users
        ];
    }), 512) ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: registrationData.map(item => item[0]),
            datasets: [{
                label: 'New Users',
                data: registrationData.map(item => item[1]),
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
        window.location.href = `<?php echo e(route('admin.reports.users')); ?>?period=` + period;
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\reports\users.blade.php ENDPATH**/ ?>