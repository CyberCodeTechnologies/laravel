

<?php $__env->startSection('title', $pageTitle ?? $moduleTitle); ?>

<?php $__env->startSection('header', $pageTitle ?? $moduleTitle); ?>

<?php $__env->startSection('admin_content'); ?>
    <div class="zoho-page-header">
        <?php if(!empty($breadcrumb)): ?>
            <div class="zoho-breadcrumb">
                <?php $__currentLoopData = $breadcrumb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $crumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($i > 0): ?><span class="sep">/</span><?php endif; ?>
                    <?php if(!empty($crumb['url'])): ?>
                        <a href="<?php echo e($crumb['url']); ?>"><?php echo e($crumb['label']); ?></a>
                    <?php else: ?>
                        <span><?php echo e($crumb['label']); ?></span>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
        <?php if(!empty($subtitle)): ?>
            <p class="subtitle"><?php echo e($subtitle); ?></p>
        <?php endif; ?>
    </div>

    <div class="zoho-toolbar">
        <div class="zoho-tabs" style="margin:0;border:none;">
            <?php $__currentLoopData = $tabs ?? ['All']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button" class="zoho-tab <?php echo e($loop->first ? 'active' : ''); ?>"><?php echo e($tab); ?></button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="zoho-toolbar-actions">
            <?php if(!empty($createLabel)): ?>
                <a href="#" class="zoho-btn zoho-btn-primary">+ <?php echo e($createLabel); ?></a>
            <?php endif; ?>
            <button type="button" class="zoho-btn zoho-btn-secondary">⋮ More</button>
        </div>
    </div>

    <div class="zoho-card">
        <?php if(!empty($filterChips)): ?>
            <div class="zoho-filter-bar">
                <?php $__currentLoopData = $filterChips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $chip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" class="zoho-filter-chip <?php echo e($i === 0 ? 'active' : ''); ?>"><?php echo e($chip); ?></button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
        <div class="zoho-card-body" style="<?php echo e(!empty($filterChips) ? 'padding-top:0;' : ''); ?>">
            <?php if(!empty($tableHeaders) && !empty($tableRows)): ?>
                <div class="zoho-table-wrap">
                    <table class="zoho-table">
                        <thead>
                            <tr>
                                <?php $__currentLoopData = $tableHeaders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th><?php echo e($header); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $tableRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td><?php echo $cell; ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="zoho-empty">
                    <p>No records yet. Click <strong>+ <?php echo e($createLabel ?? 'New'); ?></strong> to get started.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\module.blade.php ENDPATH**/ ?>