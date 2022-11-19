<?php

namespace App\Notifications;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDomainRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $requestedDomain;

    protected Tenant $tenant;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $requestedDomain, Tenant $tenant)
    {
        $this->requestedDomain = $requestedDomain;
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
            ->subject($this->requestedDomain.' domain requested from '.config('app.name'))
            ->greeting($this->tenant->name.' has requested a new domain!')
            ->line('The requested domain is: '.$this->requestedDomain)
            ->action('View all domain requests', route('domain-requests.index'))
            ->line('Thank you!');
    }
}
