<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserList extends Notification
{
    use Queueable;


    public $usernames;

    /**
     * Create a new notification instance.
     */
    public function __construct($usernames)
    {
        $this->usernames = $usernames;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(__('User List'))
            ->line(__('You have requested a list of usernames. Here it is:'))
            ->line(str('<ul>' . implode('', array_map(fn ($v) => '<li>' . htmlspecialchars($v) . '</li>', $this->usernames)) . '</ul>')->toHtmlString())
            ->line(__('You can try to login now.'))
            ->action(__('Login'), route('login'));
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
}
