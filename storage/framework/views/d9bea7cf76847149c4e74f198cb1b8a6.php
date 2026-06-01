

<?php $__env->startSection('title', 'Security Dashboard'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Security Dashboard</h1>
            <p class="text-muted">Monitor and manage application security</p>
        </div>
    </div>

    <!-- Security Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Today's Events</h5>
                    <h2><?php echo e($stats['total_events_today']); ?></h2>
                    <small>Security events logged today</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Critical Alerts</h5>
                    <h2><?php echo e($stats['critical_alerts']); ?></h2>
                    <small>Active security alerts</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Failed Logins</h5>
                    <h2><?php echo e($stats['failed_logins_today']); ?></h2>
                    <small>Failed login attempts today</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Active Sessions</h5>
                    <h2><?php echo e($stats['active_sessions']); ?></h2>
                    <small>Currently active user sessions</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo e(route('admin.security.events')); ?>" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-list"></i> View Events Log
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo e(route('admin.security.alerts')); ?>" class="btn btn-outline-danger btn-block">
                                <i class="fas fa-exclamation-triangle"></i> Security Alerts
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo e(route('admin.security.sessions')); ?>" class="btn btn-outline-info btn-block">
                                <i class="fas fa-users"></i> Active Sessions
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo e(route('admin.security.settings')); ?>" class="btn btn-outline-secondary btn-block">
                                <i class="fas fa-cog"></i> Security Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Events & Alerts -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Recent Security Events</h5>
                    <a href="<?php echo e(route('admin.security.events')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if(count($recentEvents) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Event</th>
                                        <th>User</th>
                                        <th>IP</th>
                                        <th>Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $recentEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(\Carbon\Carbon::parse($event['timestamp'])->diffForHumans()); ?></td>
                                            <td><?php echo e($event['event']); ?></td>
                                            <td>
                                                <?php if($event['user_id']): ?>
                                                    <?php echo e($event['user_role']); ?> #<?php echo e($event['user_id']); ?>

                                                <?php else: ?>
                                                    <span class="text-muted">Guest</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($event['ip']); ?></td>
                                            <td>
                                                <?php if($event['level'] === 'critical'): ?>
                                                    <span class="badge bg-danger">Critical</span>
                                                <?php elseif($event['level'] === 'warning'): ?>
                                                    <span class="badge bg-warning">Warning</span>
                                                <?php else: ?>
                                                    <span class="badge bg-info">Info</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No recent security events.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Active Security Alerts</h5>
                    <a href="<?php echo e(route('admin.security.alerts')); ?>" class="btn btn-sm btn-outline-danger">Manage</a>
                </div>
                <div class="card-body">
                    <?php if(count($activeAlerts) > 0): ?>
                        <?php $__currentLoopData = $activeAlerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="alert alert-danger alert-sm mb-2">
                                <strong><?php echo e($alert['event']); ?></strong>
                                <br>
                                <small class="text-muted">
                                    <?php echo e(\Carbon\Carbon::parse($alert['timestamp'])->diffForHumans()); ?>

                                    <?php if($alert['user_role']): ?> by <?php echo e($alert['user_role']); ?> <?php endif; ?>
                                </small>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <p class="text-muted">No active security alerts.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\security\dashboard.blade.php ENDPATH**/ ?>