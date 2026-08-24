<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserSuspended extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reason;

    public function __construct($reason = null)
    {
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Account Suspended')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your account has been suspended.')
            ->line($this->reason ? 'Reason: ' . $this->reason : '')
            ->line('If you believe this is an error, please contact support.')
            ->action('Contact Support', url('/contact'));
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Account Suspended',
            'message' => 'Your account has been suspended.' . ($this->reason ? ' Reason: ' . $this->reason : ''),
        ];
    }
}
