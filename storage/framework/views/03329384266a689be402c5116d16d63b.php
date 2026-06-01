<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Authenticity</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Georgia', serif;
            background: #f5f5f5;
            padding: 40px;
        }
        .certificate {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 60px;
            border: 20px solid #d4af37;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 32px;
            font-style: italic;
            color: #d4af37;
            margin-bottom: 20px;
        }
        .content {
            text-align: center;
            line-height: 1.8;
            margin-bottom: 40px;
        }
        .artwork-title {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }
        .details {
            margin: 30px 0;
            text-align: left;
            padding: 20px;
            background: #f9f9f9;
            border-left: 4px solid #d4af37;
        }
        .details p {
            margin: 10px 0;
        }
        .qr-code {
            text-align: center;
            margin: 30px 0;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #d4af37;
        }
        .signature {
            margin-top: 30px;
        }
        .date {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">
            <div class="logo">PANCHI GALLERY</div>
            <div class="title">Certificate of Authenticity</div>
        </div>
        
        <div class="content">
            <p>This certifies that</p>
            <div class="artwork-title"><?php echo e($artwork->title ?? 'Artwork Title'); ?></div>
            <p>is an authentic original artwork created by</p>
            <p style="font-size: 20px; font-weight: bold; margin: 20px 0;"><?php echo e($artwork->artist->name ?? 'Artist Name'); ?></p>
        </div>
        
        <div class="details">
            <p><strong>Certificate ID:</strong> <?php echo e($certificate->certificate_code ?? 'CERT-' . strtoupper(Str::random(8))); ?></p>
            <p><strong>Medium:</strong> <?php echo e($artwork->medium ?? 'Not specified'); ?></p>
            <p><strong>Dimensions:</strong> <?php echo e($artwork->dimensions ?? 'Not specified'); ?></p>
            <p><strong>Year:</strong> <?php echo e($artwork->year ?? date('Y')); ?></p>
            <p><strong>Date Issued:</strong> <?php echo e(now()->format('F d, Y')); ?></p>
        </div>
        
        <div class="qr-code">
            <p>Verify this certificate online at</p>
            <p style="font-weight: bold;">panchigallery.com/verify</p>
            <p style="margin-top: 10px;">Certificate Code: <?php echo e($certificate->certificate_code ?? 'CERT-' . strtoupper(Str::random(8))); ?></p>
        </div>
        
        <div class="footer">
            <div class="signature">
                <p>This certificate guarantees the authenticity of the artwork</p>
                <p style="margin-top: 10px; font-weight: bold;">Panchi Gallery</p>
                <p class="date"><?php echo e(now()->format('F d, Y')); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\certificates\template.blade.php ENDPATH**/ ?>