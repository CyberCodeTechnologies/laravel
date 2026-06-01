<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <!-- Header -->
        <div style="text-align: center; padding: 20px 0; border-bottom: 2px solid #000;">
            <h1 style="margin: 0; font-size: 24px;">Panchi Gallery</h1>
        </div>

        <!-- Order Confirmation -->
        <div style="padding: 30px 0;">
            <h2 style="color: #000;">Order Confirmation</h2>
            <p>Thank you for your purchase! Your order has been successfully placed.</p>

            <!-- Order Details -->
            <div style="background: #f5f5f5; padding: 20px; margin: 20px 0; border-radius: 5px;">
                <h3 style="margin-top: 0;">Order Details</h3>
                <p><strong>Order Number:</strong> <?php echo e($order->formatted_order_number); ?></p>
                <p><strong>Date:</strong> <?php echo e($order->created_at->format('F j, Y, g:i a')); ?></p>
                <p><strong>Total Amount:</strong> <?php echo e($order->formatted_total); ?></p>
                <p><strong>Payment Method:</strong> <?php echo e(ucfirst($order->payment_method)); ?></p>
                <p><strong>Shipping Method:</strong> <?php echo e(ucfirst($order->shipping_method ?? 'standard')); ?></p>
            </div>

            <!-- Shipping Information -->
            <div style="margin: 20px 0;">
                <h3>Shipping Information</h3>
                <p>
                    <?php echo e($order->full_name); ?><br>
                    <?php echo e($order->address); ?><br>
                    <?php echo e($order->city); ?>, <?php echo e($order->state ?? ''); ?> <?php echo e($order->postal_code); ?><br>
                    <?php echo e($order->country); ?>

                </p>
            </div>

            <!-- Order Items -->
            <div style="margin: 20px 0;">
                <h3>Order Items</h3>
                <?php if($order->items->count() > 0): ?>
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div style="padding: 10px 0; border-bottom: 1px solid #ddd;">
                            <p style="margin: 0;"><strong><?php echo e($item->artwork_title); ?></strong></p>
                            <p style="margin: 5px 0; color: #666;">Quantity: <?php echo e($item->quantity); ?> × <?php echo e($item->formatted_price); ?></p>
                            <p style="margin: 5px 0; font-weight: bold;"><?php echo e($item->formatted_subtotal); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Next Steps -->
        <div style="background: #e8f5e9; padding: 20px; margin: 20px 0; border-radius: 5px;">
            <h3 style="margin-top: 0; color: #2e7d32;">What's Next?</h3>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>You will receive a confirmation email shortly</li>
                <li>We will process your order within 1-2 business days</li>
                <li>You will be notified when your order ships</li>
                <li>Each artwork includes a Certificate of Authenticity</li>
            </ul>
        </div>

        <!-- Footer -->
        <div style="text-align: center; padding: 20px 0; border-top: 1px solid #ddd; margin-top: 30px; color: #666;">
            <p style="margin: 0;">© <?php echo e(date('Y')); ?> Panchi Gallery. All rights reserved.</p>
            <p style="margin: 10px 0 0;">
                <a href="<?php echo e(route('home')); ?>" style="color: #000;">Visit Our Gallery</a>
            </p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\emails\order-confirmation.blade.php ENDPATH**/ ?>