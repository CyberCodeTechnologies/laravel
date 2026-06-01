<?php
    $adminUrl = $adminUrl ?? fn (string $name, array $params = []) => route($name, $params);
    $user = auth()->user();
    $initials = strtoupper(substr($user->name ?? 'A', 0, 2));
?>
<header class="zoho-topbar">
    <button type="button" class="zoho-topbar-toggle" data-zoho-sidebar-toggle aria-label="Toggle sidebar">
        <?php echo $__env->make('admin.partials.icons', ['icon' => 'menu'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </button>
    <span class="zoho-topbar-module"><?php echo e($moduleTitle ?? 'Home'); ?></span>

    <div class="zoho-topbar-search">
        <span class="search-icon"><?php echo $__env->make('admin.partials.icons', ['icon' => 'search'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span>
        <input type="search"
               placeholder="Search in Panchi Gallery (/)"
               data-zoho-search
               data-search-url="<?php echo e($adminUrl('admin.users')); ?>"
               aria-label="Search">
    </div>

    <div class="zoho-topbar-actions">
        <div class="zoho-dropdown" data-zoho-dropdown>
            <button type="button" class="zoho-topbar-btn org-btn" data-zoho-dropdown-trigger>
                <span class="org-name">Panchi Gallery</span>
                <?php echo $__env->make('admin.partials.icons', ['icon' => 'chevron-down'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </button>
            <div class="zoho-dropdown-menu">
                <div class="zoho-dropdown-header">Organisation</div>
                <span class="zoho-dropdown-item" style="cursor:default;">Panchi Gallery</span>
            </div>
        </div>

        <div class="zoho-dropdown" data-zoho-dropdown>
            <button type="button" class="zoho-topbar-btn quick-create" data-zoho-dropdown-trigger aria-label="Quick Create" title="Quick Create">
                <?php echo $__env->make('admin.partials.icons', ['icon' => 'plus'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </button>
            <div class="zoho-dropdown-menu">
                <div class="zoho-dropdown-header">Quick Create</div>
                <a href="<?php echo e(route('admin.users.create')); ?>" class="zoho-dropdown-item">New User</a>
                <a href="<?php echo e(route('admin.artworks.create')); ?>" class="zoho-dropdown-item">New Artwork</a>
                <a href="<?php echo e(route('admin.categories.create')); ?>" class="zoho-dropdown-item">New Category</a>
                <a href="<?php echo e(route('admin.collections.create')); ?>" class="zoho-dropdown-item">New Collection</a>
            </div>
        </div>

        <div class="zoho-dropdown" data-zoho-dropdown>
            <button type="button" class="zoho-topbar-btn icon-only" data-zoho-dropdown-trigger aria-label="Notifications" title="Notifications">
                <?php echo $__env->make('admin.partials.icons', ['icon' => 'bell'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <span class="notif-dot"></span>
            </button>
            <div class="zoho-dropdown-menu" style="min-width:320px;">
                <div class="zoho-dropdown-header">Notifications</div>
                <a href="<?php echo e(route('admin.users.pending')); ?>" class="zoho-dropdown-item">Pending user approvals</a>
                <a href="<?php echo e(route('admin.artworks.pending')); ?>" class="zoho-dropdown-item">Pending artworks</a>
                <a href="<?php echo e(route('admin.pending-artists')); ?>" class="zoho-dropdown-item">Pending artists</a>
                <div class="zoho-dropdown-divider"></div>
                <a href="<?php echo e(route('admin.support.contacts')); ?>" class="zoho-dropdown-item">Support contacts</a>
            </div>
        </div>

        <a href="<?php echo e($adminUrl('admin.settings')); ?>" class="zoho-topbar-btn icon-only" aria-label="Settings" title="Settings">
            <?php echo $__env->make('admin.partials.icons', ['icon' => 'gear'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </a>

        <div class="zoho-dropdown" data-zoho-dropdown>
            <button type="button" class="zoho-topbar-btn" data-zoho-dropdown-trigger aria-label="Profile">
                <span class="zoho-avatar"><?php echo e($initials); ?></span>
            </button>
            <div class="zoho-dropdown-menu">
                <span class="zoho-dropdown-item" style="cursor:default;font-weight:600;"><?php echo e($user->name); ?></span>
                <span class="zoho-dropdown-item" style="cursor:default;font-size:11px;color:var(--zoho-text-muted);"><?php echo e($user->email); ?></span>
                <div class="zoho-dropdown-divider"></div>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="zoho-dropdown-item">Sign Out</button>
                </form>
            </div>
        </div>
    </div>
</header>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\partials\topbar.blade.php ENDPATH**/ ?>