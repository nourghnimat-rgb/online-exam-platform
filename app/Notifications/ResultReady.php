<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ResultReady extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $examTitle, public int $attemptId)
    {
        //
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

   
    public function toArray(object $notifiable): array
    {

        return [
            'attempt_id' => $this->attemptId,
            'message'    => 'Your result for "' . $this->examTitle . '" is ready.',
        ];
    }
}
