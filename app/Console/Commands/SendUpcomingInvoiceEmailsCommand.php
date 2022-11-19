<?php

namespace App\Console\Commands;

use App\Models\Plan;
use App\Models\Tenant;
use App\Notifications\InvoiceReminderEmailNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class SendUpcomingInvoiceEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upcoming-invoice-email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monthly invoice email reminder';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Tenant::whereNull('trial_ends_at')
            ->whereNull('plan_ends_at')
            ->chunk(200, function ($tenants) {
                foreach ($tenants as $tenant) {
                    $upcomingInvoices = $tenant->upcomingInvoice();
                    foreach ($upcomingInvoices->lines->data as $invoice) {
                        $details = [];
                        $details['plan_name'] = Plan::where('api_id', $invoice->plan->id)->first()->name;
                        $details['invoice_id'] = $invoice->id;
                        $details['start_date'] = Carbon::parse($invoice->period->start)->toFormattedDateString();
                        $details['end_date'] = Carbon::parse($invoice->period->end)->toFormattedDateString();
                        $details['total_amount'] = (float) $invoice->amount / 100;
                        $details['amount_excluding_tax'] = (float) $invoice->amount_excluding_tax / 100;
                        $details['currency'] = $invoice->currency;
                        $details['description'] = $invoice->description;
                        $details['quantity'] = $invoice->quantity;
                        Notification::send($tenant, new InvoiceReminderEmailNotification($details));
                    }
                }
            });

        $this->info('Upcoming invoice emails queued.');

        return 0;
    }
}
