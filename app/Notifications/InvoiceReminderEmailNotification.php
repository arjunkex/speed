<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class InvoiceReminderEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
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
        $currency = Str::upper($this->details['currency']);

        return (new MailMessage)
            ->line('Hi your invoice for Plan: '.$this->details['plan_name'].' is upcoming at: '.$this->details['start_date'])
            ->line('This subscription will be cancelled after the end of the billing period. End date: '.$this->details['end_date'])
            ->line('Total amount: '.$this->details['total_amount'].' '.$currency)
            ->line('Amount excluding tax: '.$this->details['amount_excluding_tax'].' '.$currency)
            ->line('Currency: '.$currency)
            ->line('Description: '.$this->details['description'])
            ->line('Quantity: '.$this->details['quantity'])
            ->line('Thank you for using our application!');
    }
}
