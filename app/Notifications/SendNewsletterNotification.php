<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class SendNewsletterNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $subscriber;

    public string $subject;

    public string $greeting;

    public string $body;

    /**
     * @param $subscriber
     * @param $subject
     * @param $greeting
     * @param $body
     */
    public function __construct($subscriber, $subject, $greeting, $body)
    {
        $this->subscriber = $subscriber;
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
            ->markdown('emails.newsletter', [
                'greeting' => $this->greeting,
                'body' => $this->body,
                'url' => URL::signedRoute('newsletter-unsubscribe', ['email' => $this->subscriber->email]),
            ]);
    }
}
