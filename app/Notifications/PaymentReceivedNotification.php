<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class PaymentReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast', 'mail'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Payment Received! 💰',
            'message' => "Payment of {$this->order->formatted_total} has been received for order #{$this->order->formatted_order_number}.",
            'order_id' => $this->order->id,
            'order_number' => $this->order->formatted_order_number,
            'amount' => $this->order->formatted_total,
            'payment_method' => $this->order->payment_method,
            'type' => 'payment_received',
        ];
    }

    public function toBroadcast($notifiable): array
    {
        return [
            'title' => 'Payment Received! 💰',
            'message' => "Payment of {$this->order->formatted_total} has been received.",
            'order_id' => $this->order->id,
            'created_at' => now()->toIso8601String(),
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('💰 Payment Received')
            ->greeting("Hello {$notifiable->first_name},")
            ->line("Payment has been received for your order #{$this->order->formatted_order_number}.")
            ->line("Payment Details:")
            ->line("- Amount: {$this->order->formatted_total}")
            ->line("- Payment Method: {$this->order->payment_method}")
            ->line("- Order Status: {$this->order->status_label}")
            ->action('View Order', route('orders.show', $this->order->id))
            ->line('Thank you for your purchase!');
    }
}
