<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StoreReservationVisitedNotification extends Notification
{
    use Queueable;

    public function __construct(public Reservation $reservation)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $userName = optional($this->reservation->user)->name ?? 'お客様';

        return [
            'type' => 'reservation.visited',
            'reservation_id' => $this->reservation->id,
            'store_id' => $this->reservation->store_id,
            'body' => "{$userName}様の予約を来店済みにしました。",
            'url' => route('store.reserve'),
            'created_at' => now()->toISOString(),
        ];
    }
}
