<?php

namespace App\Services;

use App\Mail\ContactArtistMail;
use App\Mail\ContactSupportMail;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class EmailService
{
    /**
     * Send contact artist email.
     *
     * @param array $data
     * @param string $artistEmail
     * @return bool
     */
    public function sendContactArtistEmail(array $data, string $artistEmail): bool
    {
        try {
            Mail::to($artistEmail)->send(new ContactArtistMail($data));
            
            Log::info('Contact artist email sent successfully', [
                'artist_email' => $artistEmail,
                'sender_email' => $data['email'],
            ]);
            
            return true;
        } catch (Exception $e) {
            Log::error('Failed to send contact artist email', [
                'artist_email' => $artistEmail,
                'sender_email' => $data['email'],
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    /**
     * Send contact support email.
     *
     * @param array $data
     * @return bool
     */
    public function sendContactSupportEmail(array $data): bool
    {
        try {
            $supportEmail = env('PLATFORM_CONTACT_EMAIL', 'support@panchigallery.com');
            
            Mail::to($supportEmail)->send(new ContactSupportMail($data));
            
            Log::info('Contact support email sent successfully', [
                'support_email' => $supportEmail,
                'sender_email' => $data['email'],
            ]);
            
            return true;
        } catch (Exception $e) {
            Log::error('Failed to send contact support email', [
                'sender_email' => $data['email'],
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    /**
     * Send order confirmation email.
     *
     * @param mixed $order
     * @return bool
     */
    public function sendOrderConfirmationEmail($order): bool
    {
        try {
            Mail::to($order->email)->send(new OrderConfirmationMail($order));
            
            Log::info('Order confirmation email sent successfully', [
                'order_id' => $order->id,
                'customer_email' => $order->email,
            ]);
            
            return true;
        } catch (Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'order_id' => $order->id,
                'customer_email' => $order->email,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    /**
     * Send email with error handling and logging.
     *
     * @param string $to
     * @param mixed $mailable
     * @param string $logContext
     * @return bool
     */
    public function sendEmail(string $to, $mailable, string $logContext = 'email'): bool
    {
        try {
            Mail::to($to)->send($mailable);
            
            Log::info("{$logContext} sent successfully", [
                'to' => $to,
            ]);
            
            return true;
        } catch (Exception $e) {
            Log::error("Failed to send {$logContext}", [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    /**
     * Check if email configuration is valid.
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        $mailer = config('mail.default');
        
        // Log and array mailers are always valid for development
        if (in_array($mailer, ['log', 'array'])) {
            return true;
        }
        
        // Check SMTP configuration
        if ($mailer === 'smtp') {
            return !empty(config('mail.mailers.smtp.host')) &&
                   !empty(config('mail.mailers.smtp.port'));
        }
        
        // Check third-party service configurations
        if ($mailer === 'mailgun') {
            return !empty(env('MAILGUN_DOMAIN')) && !empty(env('MAILGUN_SECRET'));
        }
        
        if ($mailer === 'postmark') {
            return !empty(env('POSTMARK_TOKEN'));
        }
        
        if ($mailer === 'resend') {
            return !empty(env('RESEND_API_KEY'));
        }
        
        if ($mailer === 'ses') {
            return !empty(env('AWS_ACCESS_KEY_ID')) && !empty(env('AWS_SECRET_ACCESS_KEY'));
        }
        
        return false;
    }

    /**
     * Get current mailer type.
     *
     * @return string
     */
    public function getCurrentMailer(): string
    {
        return config('mail.default', 'log');
    }

    /**
     * Get support email address.
     *
     * @return string
     */
    public function getSupportEmail(): string
    {
        return env('PLATFORM_CONTACT_EMAIL', 'support@panchigallery.com');
    }
}
