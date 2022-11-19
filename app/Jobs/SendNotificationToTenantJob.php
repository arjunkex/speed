<?php

namespace App\Jobs;

use App\Notifications\SendNotificationToTenantNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendNotificationToTenantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $tenant;

    public $subject;

    public $greeting;

    public $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tenant, $subject, $greeting, $body)
    {
        $this->tenant = $tenant;
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
        Notification::send($this->tenant,
            new SendNotificationToTenantNotification($this->tenant, $this->subject, $this->greeting, $this->body));
    }
}
