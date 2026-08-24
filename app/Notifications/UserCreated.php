<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to Our Platform')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your account has been created successfully.')
            ->line('You can now login using your email and password.')
            ->action('Login', url('/login'))
            ->line('Thank you for joining us!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Account Created',
            'message' => 'Your account has been created successfully.',
            'url' => url('/dashboard'),
        ];
    }
}
