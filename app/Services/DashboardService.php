<?php

namespace App\Services;

use App\Interfaces\IDashboardService;
use Carbon\Carbon;

use App\Models\AccountTransaction;
use App\Models\BalanceTansfer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\InvoiceReturn;
use App\Models\LoanPayment;
use App\Models\NonInvoicePayment;
use App\Models\NonPurchasePayment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\PurchaseReturn;
use Illuminate\Support\Facades\DB;


class DashboardService implements IDashboardService
{

    public function getSummery($summeryType)
    {
        $to = Carbon::today();
        $from = Carbon::today();

        if ($summeryType == 'today') {

            $from = Carbon::today();

        } elseif ($summeryType == 'last_7_days') {

            $from = Carbon::now()->subDays(7);

        } elseif ($summeryType == 'this_month') {

            $from = Carbon::now()->startOfMonth();

        } elseif ($summeryType == 'this_year') {
            $from = Carbon::now()->startOfYear();

        }

        return $this->getSummeryBetweenDates($from, $to);
    }

    public function getSummeryBetweenDates($from, $to)
    {

        $invoicePayment = InvoicePayment::where('status', 1)->whereBetween('date', [$from, $to])->sum('amount');
        $nonInvoicePayment = NonInvoicePayment::where('type', 1)->where('status', 1)->whereBetween('date', [$from, $to])->sum('amount');

        // payment sent(Purchase + Nonpurhcase)
        $purchasePayment = PurchasePayment::where('status', 1)->whereBetween('date', [$from, $to])->sum('amount');
        $nonPurchasePayment = NonPurchasePayment::where('status', 1)->whereBetween('date', [$from, $to])->sum('amount');

        // expenses
        $expenses = Expense::select(DB::raw('SUM(account_transactions.amount) As expAmount'))
            ->leftJoin('account_transactions', 'account_transactions.id', '=', 'expenses.transaction_id')
            ->where('expenses.status', 1)
            ->whereBetween('expenses.date', [$from, $to])
            ->get();

        return [
            'purchaseAmount' => Purchase::where('status', 1)->whereBetween('purchase_date', [$from, $to])->get()->sum('calculated_total'),
            'purchaseReturnAmount' => PurchaseReturn::where('status', 1)->whereBetween('date', [$from, $to])->sum('total_return'),
            'salesAmount' => Invoice::where('status', 1)->whereBetween('invoice_date', [$from, $to])->get()->sum('calculated_total'),
            'salesReturnAmount' => InvoiceReturn::where('status', 1)->whereBetween('date', [$from, $to])->sum('total_return'),
            'paymentReceived' => $invoicePayment + $nonInvoicePayment,
            'paymentSent' => $purchasePayment + $nonPurchasePayment,
            'expenseAmount' => round($expenses[0]->expAmount),
            'balanceTransfer' => BalanceTansfer::where('status', 1)->whereBetween('date', [$from, $to])->sum('amount'),
        ];
    }
}
