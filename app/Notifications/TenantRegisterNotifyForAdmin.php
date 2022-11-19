<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantRegisterNotifyForAdmin extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    protected object $thread;
    protected string $domainWithHost;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(object $thread, string $domainWithHost)
    {
        $this->thread = $thread;
        $this->domainWithHost = $domainWithHost;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'broadcast', 'database'];
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
            ->greeting('Hello, '.ucfirst($notifiable->name))
            ->subject('New Vendor Registered')
            ->line('A new vendor has been registered.')
            ->line('Name: '.$this->thread['name'])
            ->line('Email: '.$this->thread['email'])
            ->line('Domain: '.$this->domainWithHost)
            ->action('View Details', url('/tenants/'.$this->thread['id']));
    }


    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->thread['name'].' just registered as a Tenant',
            'url' => url('/tenants/'.$this->thread['id']),
        ]);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('new-tenant-registered');
    }

    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'message' => $this->thread['name'].' just registered as a Tenant',
            'url' => url('/tenants/'.$this->thread['id']),
        ]);
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
