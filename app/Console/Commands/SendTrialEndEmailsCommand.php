<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Notifications\TrialEndsEmailNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendTrialEndEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trial-ends-email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send trial end emails to users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Send trial end emails to tenants who have a trial ending in the next 24 hours.
        Tenant::whereNotNull('trial_ends_at')
            ->whereNull('data->trial_ends_email_sent_at')
            ->whereDate('trial_ends_at', Carbon::tomorrow())
            ->chunk(200, function ($tenants) {
                foreach ($tenants as $tenant) {
                    if ($tenant->trial_ends_at->isTomorrow()) {
                        $tenant->update(['trial_ends_email_sent_at' => now()]);
                        Notification::send($tenant, new TrialEndsEmailNotification($tenant));
                    }
                }
            });

        $this->info('Trial end emails queued.');

        return 0;
    }
}
