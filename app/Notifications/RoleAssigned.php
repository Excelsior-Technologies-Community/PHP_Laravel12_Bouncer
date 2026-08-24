<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class RoleAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Role Assigned')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have been assigned the role: ' . $this->role)
            ->line('You now have access to new features based on this role.')
            ->action('View Dashboard', url('/dashboard'))
            ->line('Thank you!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Role Assigned',
            'message' => 'You have been assigned the role: ' . $this->role,
            'url' => url('/dashboard'),
        ];
    }
}
