<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php $__currentLoopData = $sitemap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <url>
        <loc><?php echo e($item['url']); ?></loc>
        <lastmod><?php echo e($item['lastmod']); ?></lastmod>
        <changefreq><?php echo e($item['changefreq']); ?></changefreq>
        <priority><?php echo e($item['priority']); ?></priority>
    </url>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</urlset>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\sitemap.blade.php ENDPATH**/ ?>