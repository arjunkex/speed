<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendNotificationToTenantNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $tenant;

    public string $subject;

    public string $greeting;

    public string $body;

    /**
     * @param $tenant
     * @param $subject
     * @param $greeting
     * @param $body
     */
    public function __construct($tenant, $subject, $greeting, $body)
    {
        $this->tenant = $tenant;
        $this->subject = $subject;
        $this->greeting = $greeting;
        $this->body = $body;
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
            ->subject($this->subject)
            ->markdown('emails.notification', [
                'greeting' => $this->greeting,
                'body' => $this->body,
            ]);
    }
}
