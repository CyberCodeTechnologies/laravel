<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class NewCustomOrderNotification extends Notification implements ShouldQueue
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
            'title' => 'New Custom Order Request! 🎨',
            'message' => "You have a new custom order request for a {$this->order->medium} artwork.",
            'order_id' => $this->order->id,
            'order_title' => $this->order->title,
            'customer_name' => $this->order->full_name,
            'medium' => $this->order->medium,
            'style' => $this->order->style,
            'proposed_price' => $this->order->formatted_total,
            'type' => 'new_custom_order',
        ];
    }

    public function toBroadcast($notifiable): array
    {
        return [
            'title' => 'New Custom Order Request! 🎨',
            'message' => "You have a new custom order request for a {$this->order->medium} artwork.",
            'order_id' => $this->order->id,
            'created_at' => now()->toIso8601String(),
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🎨 New Custom Order Request')
            ->greeting("Hello {$notifiable->first_name},")
            ->line("You have received a new custom artwork order request.")
            ->line("Order Details:")
            ->line("- Title: {$this->order->title}")
            ->line("- Medium: {$this->order->medium}")
            ->line("- Style: {$this->order->style}")
            ->line("- Size: {$this->order->size}")
            ->line("- Proposed Price: {$this->order->formatted_total}")
            ->line("- Customer: {$this->order->full_name}")
            ->action('View Order', route('custom-orders.show', $this->order->id))
            ->line('Please review and accept or reject this order.');
    }
}
