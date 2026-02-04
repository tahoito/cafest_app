<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StoreReviewPostedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Review $review) 
    {
        return ['database'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable): array {

        $lastChecked = $notifiable->last_review_checked_at;

        $newCount = Review::where('store_id', $this->review->store_id)
            ->where($lastChecked, fn($q) => $q-where('created_at','>', $lastChecked))
            ->count();


        return [
            'type' => 'review.posted',
            'review_id' => $this->review->id,
            'store_id' => $this->review->store_id,
            'new_count' => $newCount,
            'body' => "レビューが追加されました",
            'url' => route('store.reviews.show', $this->review),
            'created_at' => now()->toISOString(),
        ];
    }
}
