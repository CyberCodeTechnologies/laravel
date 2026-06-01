<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Message from Panchi Gallery</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background-color: #000; padding: 20px; text-align: center;">
            <h1 style="color: #fff; margin: 0; font-size: 24px;">Panchi Gallery</h1>
        </div>
        
        <div style="padding: 30px; background-color: #fff;">
            <h2 style="margin-top: 0;">New Message Received</h2>
            
            <p>Hello {{ $data['artist_name'] }},</p>
            
            <p>You have received a new message through Panchi Gallery:</p>
            
            <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">From:</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $data['name'] }} ({{ $data['email'] }})</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">Subject:</td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $data['subject'] }}</td>
                </tr>
            </table>
            
            <div style="background-color: #f9f9f9; padding: 20px; border-left: 4px solid #000; margin: 20px 0;">
                <p style="margin: 0; white-space: pre-wrap;">{{ $data['message'] }}</p>
            </div>
            
            <p>Please respond to this message directly by replying to the sender at {{ $data['email'] }}.</p>
            
            <p style="margin-top: 30px; font-size: 12px; color: #666;">
                This message was sent through Panchi Gallery. If you believe this is an error, please contact support@panchigallery.com.
            </p>
        </div>
        
        <div style="background-color: #f5f5f5; padding: 20px; text-align: center; font-size: 12px; color: #666;">
            <p>&copy; {{ date('Y') }} Panchi Gallery. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
