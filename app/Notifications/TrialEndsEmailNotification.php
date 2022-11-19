<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrialEndsEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $tenant;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your trial ends tomorrow!')
            ->greeting('Dear '.$this->tenant->name.',')
            ->line('Your trial will end tomorrow! 😩')
            ->line('Please upgrade your account if you want to continue using our service.')
            ->action('Go to website', url('/login'))
            ->line('Thank you for using our application!');
    }
}
