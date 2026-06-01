<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Artwork;
use App\Models\Transaction;

class ArtworkSoldNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Artwork $artwork;
    protected Transaction $transaction;

    public function __construct(Artwork $artwork, Transaction $transaction)
    {
        $this->artwork = $artwork;
        $this->transaction = $transaction;
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Artwork Sold! 🎉',
            'message' => "Your artwork '{$this->artwork->title}' has been sold for {$this->transaction->formatted_amount}.",
            'artwork_id' => $this->artwork->id,
            'artwork_title' => $this->artwork->title,
            'artwork_image' => $this->artwork->images[0] ?? null,
            'transaction_id' => $this->transaction->id,
            'amount' => $this->transaction->formatted_amount,
            'buyer_name' => $this->transaction->buyer->full_name,
            'type' => 'artwork_sold',
        ];
    }

    public function toBroadcast($notifiable): array
    {
        return [
            'title' => 'Artwork Sold! 🎉',
            'message' => "Your artwork '{$this->artwork->title}' has been sold for {$this->transaction->formatted_amount}.",
            'artwork_id' => $this->artwork->id,
            'transaction_id' => $this->transaction->id,
            'created_at' => now()->toIso8601String(),
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🎉 Your Artwork Has Been Sold!')
            ->greeting("Hello {$notifiable->first_name},")
            ->line("Great news! Your artwork '{$this->artwork->title}' has been sold.")
            ->line("Sale Details:")
            ->line("- Artwork: {$this->artwork->title}")
            ->line("- Sold Price: {$this->transaction->formatted_amount}")
            ->line("- Buyer: {$this->transaction->buyer->full_name}")
            ->action('View Transaction', route('transactions.show', $this->transaction->id))
            ->line('Thank you for being part of PanchiGallery!');
    }
}
