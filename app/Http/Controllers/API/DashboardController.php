<?php

namespace App\Http\Controllers\API;

use DateTime;
use Exception;
use Carbon\Carbon;

use App\Http\Requests\Profile\UpdateProfileRequest;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use App\Models\InvoicePayment;
use App\Models\PurchasePayment;
use App\Models\NonInvoicePayment;
use App\Models\AccountTransaction;
use App\Models\NonPurchasePayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Interfaces\IDashboardService;
use App\Http\Resources\ExpenseResource;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Artisan;
use App\Http\Resources\InvoiceListResource;
use App\Http\Resources\PurchaseListReource;
use App\Http\Resources\ProductListingResource;
use App\Http\Resources\AccountTransactionResource;

class DashboardController extends Controller
{
    protected $dashboardService;
    // define middleware
    public function __construct(IDashboardService $dashboardService)
    {
        $this->middleware('can:account-summery', ['only' => ['dashboardSummery', 'getTodayOrThisWeekSummery', 'getThisMonthSummery', 'getThisYearSummery']]);
        $this->middleware('can:top-selling-products', ['only' => ['topSellingProducts']]);
        $this->middleware('can:recent-activities', ['only' => ['recentInvoices', 'recentPurchases', 'recentExpenses', 'recentTransactions']]);
        $this->middleware('can:payment-sent-vs-payment-received', ['only' => ['monthlyPaymentSentAndReceived']]);
        $this->middleware('can:top-clients', ['only' => ['topClients']]);
        $this->middleware('can:stock-alert', ['only' => ['stockAlert']]);
        $this->middleware('can:sales-vs-purchases', ['only' => ['monthlySalesAndPurchases']]);
        $this->middleware('can:database-backup', ['only' => ['databaseBackup']]);
        $this->dashboardService = $dashboardService;
    }

    // return dashboard summery
    public function dashboardSummery($summeryType)
    {
        return $this->dashboardService->getSummery($summeryType);
    }

    // return top selling products
    public function topSellingProducts()
    {
        $year = date('Y');
        if (Invoice::count() > 0) {
            $sales = DB::table('products')
                ->leftJoin('invoice_products', 'products.id', '=', 'invoice_products.product_id')
                ->selectRaw('COALESCE(sum(invoice_products.quantity),0) value, products.name')
                ->whereYear('invoice_products.created_at', '=', $year)
                ->groupBy('products.id')
                ->orderBy('value', 'desc')
                ->take(5)
                ->get();

            $productNames = [];
            foreach ($sales as $key => $item) {
                array_push($productNames, $item->name);
            }

            return [
                'names' => $productNames,
                'products' => $sales,
            ];
        }
    }

    // return sales
    public function recentInvoices()
    {
        return InvoiceListResource::collection(Invoice::with('client', 'invoiceTax', 'invoicePayments')->latest()->take(6)->get());
    }

    // return purchases
    public function recentPurchases()
    {
        return PurchaseListReource::collection(Purchase::with('supplier', 'purchasePayments', 'purchaseTax')->latest()->take(6)->get());
    }

    // return expenses
    public function recentExpenses()
    {
        return ExpenseResource::collection(Expense::with('expSubCategory.expCategory', 'expTransaction.cashbookAccount', 'user')->latest()->take(6)->get());
    }

    // return transactions
    public function recentTransactions()
    {
        $transactions = AccountTransaction::with('cashbookAccount', 'user')->latest()->take(6)->get();

        return AccountTransactionResource::collection($transactions);
    }

    // return monthly payment sent and received
    public function monthlyPaymentSentAndReceived()
    {
        $year = date('Y');
        $monthNum = date('m');
        $shortMonthNames = [];
        $sentByMonth = [];
        $receivedByMonth = [];

        while ($monthNum > 0) {
            // get the monthly payment sent amount
            $purchasePayment = PurchasePayment::where('status', 1)->whereYear('date', $year)->whereMonth('date', $monthNum)->sum('amount');
            $nonPurchasePayment = NonPurchasePayment::where('status', 1)->where('type', 1)->whereYear('date', $year)->whereMonth('date', $monthNum)->sum('amount');
            $termLoanPayment = LoanPayment::with('loan')->where('status', 1)->whereYear('date', $year)->whereMonth('date', $monthNum)->whereHas('loan', function ($newQuery) {
                $newQuery->where('loan_type', 1);
            })->sum('amount');
            $ccLoanPayment = LoanPayment::with('loan')->where('status', 1)->whereYear('date', $year)->whereMonth('date', $monthNum)->whereHas('loan', function ($newQuery) {
                $newQuery->where('loan_type', 0);
            })->sum(DB::raw('amount + interest'));
            $totalPaymentSent = $purchasePayment + $nonPurchasePayment + $termLoanPayment + $ccLoanPayment;

            // get the monthly received amount
            $invoicePayment = InvoicePayment::where('status', 1)->whereYear('date', $year)->whereMonth('date', $monthNum)->sum('amount');
            $nonInvoicePayment = NonInvoicePayment::where('type', 1)->where('status', 1)->whereYear('date', $year)->whereMonth('date', $monthNum)->sum('amount');
            $loanPayment = DB::table('account_transactions')
                ->leftJoin('loans', 'account_transactions.id', '=', 'loans.transaction_id')
                ->where('loans.status', 1)->whereYear('loans.date', $year)->whereMonth('loans.date', $monthNum)
                ->sum('account_transactions.amount');
            $totalPaymentSentReceived = $invoicePayment + $nonInvoicePayment + $loanPayment;

            // make the months array
            $dateObj = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('M');

            // push into array
            if ($totalPaymentSent > 0 || $totalPaymentSentReceived > 0) {
                array_push($sentByMonth, round($totalPaymentSent, 2));
                array_push($receivedByMonth, round($totalPaymentSentReceived, 2));
                array_push($shortMonthNames, $monthName);
            }
            $monthNum--;
        }

        return [
            'months' => array_reverse($shortMonthNames),
            'sent' => array_reverse($sentByMonth),
            'received' => array_reverse($receivedByMonth),
        ];
    }

    // return top clients
    public function topClients()
    {
        $year = date('Y');
        $topCustomers = Invoice::with('client')->whereYear('invoice_date', '=', $year)->addSelect(DB::raw('COUNT(invoices.id) as total_invoice'), DB::raw('SUM(sub_total) as invoice_total, client_id'))->groupBy('client_id')->take(5)->orderBy('invoice_total', 'DESC')->get();

        return $topCustomers;
    }

    // return stock alert products
    public function stockAlert()
    {
        return ProductResource::collection(Product::with('proSubCategory.category', 'productUnit', 'productTax', 'productBrand')->orderBy('inventory_count', 'ASC')->take(6)->get());
    }

    // return monthly sales and purchases
    public function monthlySalesAndPurchases()
    {
        $year = date('Y');
        $monthNum = date('m');
        $shortMonthNames = [];
        $purchaseByMonth = [];
        $salesByMonth = [];

        while ($monthNum > 0) {
            // get the monthly purchase amount
            $purchaseAmount = Purchase::where('status', 1)->whereYear('purchase_date', $year)->whereMonth('purchase_date', $monthNum)->get()->sum('calculated_total');

            // get the monthly sales amount
            $salesAmount = Invoice::where('status', 1)->whereYear('invoice_date', $year)->whereMonth('invoice_date', $monthNum)->get()->sum('calculated_total');

            // make the months array
            $dateObj = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('M');

            // push into array
            if ($purchaseAmount > 0 || $salesAmount > 0) {
                array_push($purchaseByMonth, round($purchaseAmount, 2));
                array_push($salesByMonth, round($salesAmount, 2));
                array_push($shortMonthNames, $monthName);
            }
            $monthNum--;
        }

        return [
            'months' => array_reverse($shortMonthNames),
            'purchase' => array_reverse($purchaseByMonth),
            'sales' => array_reverse($salesByMonth),
        ];
    }

    // get stock notification
    public function stockNotification()
    {
        return Product::whereRaw('alert_qty > inventory_count')->count();
    }

    // get products with stock alert
    public function stockAlertProducts()
    {
        return ProductListingResource::collection(Product::whereRaw('alert_qty > inventory_count')->with('proSubCategory.category', 'productUnit', 'productTax', 'productBrand')->latest()->paginate(10));
    }

    // get products with stock alert
    public function searchStockAlertProducts(Request $request)
    {
        $term = $request->term;

        $products = Product::where('alert_qty', '>', 'inventory_count')->with('proSubCategory.category', 'productUnit', 'productTax', 'productBrand')->where(function ($query) use ($term) {
            $query->where('name', 'LIKE', '%' . $term . '%')
                ->orWhere('slug', 'LIKE', '%' . $term . '%')
                ->orWhere('model', 'LIKE', '%' . $term . '%')
                ->orWhere('code', 'LIKE', '%' . $term . '%')
                ->orWhereHas('proSubCategory', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%' . $term . '%')
                        ->orWhereHas('category', function ($newQuery) use ($term) {
                            $newQuery->where('name', 'LIKE', '%' . $term . '%');
                        });
                });
        })->latest()->paginate(10);

        return ProductListingResource::collection($products);
    }

    // update user profile
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth()->user();

        try {

            $password = $user->password;
            if ($request->newPassword) {
                $password = bcrypt($request->newPassword);
            }

            $user->update([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => $password,
            ]);

            return $this->responseWithSuccess('Profile updated successfully');
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    // create database backup
    public function databaseBackup(Request $request)
    {
        $folderPath = storage_path() . '//backup/';
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true, true);
        }

        $fileName = \DB::connection()->getDatabaseName() . '_' . Carbon::now()->getTimestamp() . '.' . $request->format;
        try {
            Artisan::call('database:backup', ['fileName' => $fileName]);
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }

        $pathToFile = $folderPath  . $fileName;

        $headers = [
            'Content-Description' => 'File Transfer',
            'Content-Type' => 'application/sql',
        ];

        return response()->download($pathToFile, $fileName, $headers);
    }
}
