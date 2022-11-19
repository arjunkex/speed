<?php

namespace App\Notifications;

use App\Models\GeneralSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSubscriptionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $domainWithHost;

    protected ?string $password = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $domainWithHost, ?string $password = null)
    {
        $this->domainWithHost = $domainWithHost;
        $this->password = $password;
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
            ->subject('Welcome to '.GeneralSetting::where('key', 'company_name')->first()->value)
            ->greeting('Welcome abroad!')
            ->line('Thank you for choosing us for your business.')
            ->action('Here\'s your new domain link', $this->domainWithHost)
            ->lineIf(!empty($this->password), 'Your email is your current email')
            ->lineIf(!empty($this->password), 'And your password is: '.$this->password)
            ->line('Thank you for using our application!');
    }
}
