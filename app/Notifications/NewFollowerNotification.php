<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class NewFollowerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected User $follower;

    public function __construct(User $follower)
    {
        $this->follower = $follower;
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'New Follower! 👤',
            'message' => "{$this->follower->full_name} started following you.",
            'follower_id' => $this->follower->id,
            'follower_name' => $this->follower->full_name,
            'follower_avatar' => $this->follower->avatar,
            'follower_url' => route('artists.show', $this->follower->slug ?? $this->follower->id),
            'type' => 'new_follower',
        ];
    }

    public function toBroadcast($notifiable): array
    {
        return [
            'title' => 'New Follower! 👤',
            'message' => "{$this->follower->full_name} started following you.",
            'follower_id' => $this->follower->id,
            'follower_name' => $this->follower->full_name,
            'created_at' => now()->toIso8601String(),
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('👤 You Have a New Follower!')
            ->greeting("Hello {$notifiable->first_name},")
            ->line("{$this->follower->full_name} has started following you on PanchiGallery.")
            ->action('View Profile', route('artists.show', $this->follower->slug ?? $this->follower->id))
            ->line('Keep creating amazing artwork!');
    }
}
