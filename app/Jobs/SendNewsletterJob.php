<?php

namespace App\Jobs;

use App\Notifications\SendNewsletterNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendNewsletterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $subscriber;

    public $subject;

    public $greeting;

    public $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subscriber, $subject, $greeting, $body)
    {
        $this->subscriber = $subscriber;
        $this->subject = $subject;
        $this->greeting = $greeting;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Notification::send($this->subscriber,
            new SendNewsletterNotification($this->subscriber, $this->subject, $this->greeting, $this->body));
    }
}
