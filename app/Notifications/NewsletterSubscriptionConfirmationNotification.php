<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class NewsletterSubscriptionConfirmationNotification extends Notification
{
    use Queueable;

    protected string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
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
        \Log::info($this->email);
        return (new MailMessage)
            ->subject('Newsletter Subscription Confirmation from '.config('app.name'))
            ->line('Please click on the button below to confirm your newsletter subscription.')
            ->action('Confirm', URL::signedRoute('newsletter-confirm', ['email' => $this->email]))
            ->line('Thank you!');
    }
}
