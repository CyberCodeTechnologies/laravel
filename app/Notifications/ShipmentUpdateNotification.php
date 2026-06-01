<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Shipment;

class ShipmentUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Shipment $shipment;
    protected string $status;

    public function __construct(Shipment $shipment, string $status)
    {
        $this->shipment = $shipment;
        $this->status = $status;
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast', 'mail'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Shipment Update! 📦',
            'message' => "Your shipment status has been updated to: {$this->status}.",
            'shipment_id' => $this->shipment->id,
            'tracking_number' => $this->shipment->tracking_number,
            'status' => $this->status,
            'order_id' => $this->shipment->order_id,
            'type' => 'shipment_update',
        ];
    }

    public function toBroadcast($notifiable): array
    {
        return [
            'title' => 'Shipment Update! 📦',
            'message' => "Your shipment status has been updated to: {$this->status}.",
            'shipment_id' => $this->shipment->id,
            'tracking_number' => $this->shipment->tracking_number,
            'created_at' => now()->toIso8601String(),
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('📦 Shipment Update')
            ->greeting("Hello {$notifiable->first_name},")
            ->line("Your shipment status has been updated.")
            ->line("Shipment Details:")
            ->line("- Tracking Number: {$this->shipment->tracking_number}")
            ->line("- Current Status: {$this->status}")
            ->line("- Carrier: {$this->shipment->carrier}")
            ->action('Track Shipment', route('shipments.track', $this->shipment->tracking_number))
            ->line('Thank you for shopping with PanchiGallery!');
    }
}
