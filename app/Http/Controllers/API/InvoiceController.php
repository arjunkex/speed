<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Http\Resources\InvoiceListResource;
use App\Http\Resources\InvoiceResource;
use App\Interfaces\ITransactionService;
use App\Models\AccountTransaction;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\InvoiceProduct;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    protected ITransactionService $transactionService;

    // define middleware
    public function __construct(ITransactionService $transactionService)
    {
        $this->middleware('can:invoice-list', ['only' => ['index', 'search']]);
        $this->middleware('can:invoice-create', ['only' => ['create']]);
        $this->middleware('can:invoice-view', ['only' => ['show']]);
        $this->middleware('can:invoice-edit', ['only' => ['update']]);
        $this->middleware('can:invoice-delete', ['only' => ['destroy']]);

        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return InvoiceListResource::collection(Invoice::with('client', 'invoiceTax', 'invoicePayments')->latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreInvoiceRequest $request)
    {
        $this->checkSubscriptionLimitByModelName('Invoice');

        try {
            // generate code
            $code = 1;
            $lastInvoice = Invoice::latest()->first();
            if ($lastInvoice) {
                $code = $lastInvoice->invoice_no + 1;
            }

            // get logged in user id
            $userId = auth()->user()->id;

            // calculate is paid
            $isPaid = 0;
            if ($request->netTotal == $request->paidAmount) {
                $isPaid = 1;
            }

            // calculate discount
            $discount = $request->discount;
            if ($request->discountType == 1) {
                $discount = $request->totalDiscount;
            }

            // create invoice
            $invoice = Invoice::create([
                'invoice_no' => $code,
                'reference' => $request->reference,
                'slug' => uniqid(),
                'client_id' => $request->client['id'],
                'transport' => $request->transportCost,
                'discount_type' => $request->discountType,
                'discount' => $discount,
                'sub_total' => $request->subTotal,
                'po_reference' => $request->poReference,
                'payment_terms' => $request->paymentTerms,
                'delivery_place' => $request->deliveryPlace,
                'tax_id' => $request->orderTax['id'],
                'invoice_date' => $request->date,
                'note' => clean($request->note),
                'status' => $request->status,
                'is_paid' => $isPaid,
                'created_by' => $userId,
            ]);

            // store invoice products
            foreach ($request->selectedProducts as $key => $selectedProduct) {
                $product = Product::where('slug', $selectedProduct['slug'])->first();
                // update product stock
                $product->update([
                    'inventory_count' => $product->inventory_count - $selectedProduct['qty'],
                ]);

                InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $selectedProduct['id'],
                    'quantity' => $selectedProduct['qty'],
                    'purchase_price' => $selectedProduct['avgPurchasePrice'],
                    'sale_price' => $selectedProduct['unitPrice'],
                    'unit_cost' => $selectedProduct['unitCost'],
                    'tax_amount' => $selectedProduct['productTax'],
                ]);
            }

            // store transaction
            if ($request->addPayment == true) {

                $transaction = $this->transactionService->createTransactionFromInvoice($request, $userId, $invoice);

                // store invoice payment record
                InvoicePayment::create([
                    'slug' => uniqid(),
                    'invoice_id' => $invoice->id,
                    'transaction_id' => $transaction->id,
                    'amount' => $request->paidAmount,
                    'date' => $request->date,
                    'note' => clean($request->note),
                    'created_by' => $userId,
                    'status' => $request->status,
                ]);
            }

            return $this->responseWithSuccess('Invoice added successfully', [
                'invoice_id' => $invoice->id,
                'invoice_slug' => $invoice->slug,
                'slug' => $invoice->slug,
            ]);
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    public function storeInvoicePayments(Request $request)
    {
        $this->validate($request, [
            'account' => 'required',
            'paidAmount' => ['required', 'min:1', 'max:' . $request->netTotal],
            'invoice_id' => ['required', 'integer'],
            'chequeNo' => 'nullable|string|max:255',
            'receiptNo' => 'nullable|string|max:255',
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        $userId = auth()->id();
        // store transaction
        $reason = '[' . config('config.invoicePrefix') . '-' . $invoice->invoice_no . '] Invoice Payment added to [' . $request->account['accountNumber'] . ']';
        try {
            // create transaction
            $transaction = AccountTransaction::create([
                'account_id' => $request->account['id'],
                'amount' => $request->paidAmount,
                'reason' => $reason,
                'type' => 1,
                'transaction_date' => $request->date,
                'cheque_no' => $request->chequeNo,
                'receipt_no' => $request->receiptNo,
                'created_by' => $userId,
                'status' => $request->status,
            ]);

            // store invoice payment record
            InvoicePayment::create([
                'slug' => uniqid(),
                'invoice_id' => $invoice->id,
                'transaction_id' => $transaction->id,
                'amount' => $request->paidAmount,
                'date' => $request->date,
                'note' => clean($request->note),
                'created_by' => $userId,
                'status' => $request->status,
            ]);

            $isPaid = 0;
            if ($request->netTotal == $request->paidAmount) {
                $isPaid = 1;
            }

            // update invoice data
            $invoice->update([
                'reference' => $request->reference,
                'po_reference' => $request->poReference,
                'payment_terms' => $request->paymentTerms,
                'delivery_place' => $request->deliveryPlace,
                'invoice_date' => $request->date,
                'is_paid' => $isPaid,
                'note' => clean($request->note),
            ]);

            return $this->responseWithSuccess('Invoice added successfully', [
                'invoice_id' => $invoice->id,
            ]);
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try {
            $invoice = Invoice::where('slug', $slug)->with('client', 'invoiceProducts.invoice', 'invoicePayments.invoicePaymentTransaction.cashbookAccount', 'invoiceProducts.product.productUnit', 'invoiceProducts.product.productTax', 'invoiceTax', 'user')->first();

            return new InvoiceResource($invoice);
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateInvoiceRequest $request, $slug)
    {
        $invoice = Invoice::where('slug', $slug)->with('invoiceProducts.product', 'invoiceReturn')->first();

        try {
            // calculate is paid
            $isPaid = 0;
            if ($request->netTotal == $request->totalPaid) {
                $isPaid = 1;
            }

            // calculate discount
            $discount = $request->discount;
            if ($request->discountType == 1) {
                $discount = $request->totalDiscount;
            }

            // update invoice
            $invoice->update([
                'reference' => $request->reference,
                'client_id' => $request->client['id'],
                'transport' => $request->transportCost,
                'discount_type' => $request->discountType,
                'discount' => $discount,
                'sub_total' => $request->subTotal,
                'po_reference' => $request->poReference,
                'payment_terms' => $request->paymentTerms,
                'delivery_place' => $request->deliveryPlace,
                'tax_id' => $request->orderTax['id'],
                'invoice_date' => $request->date,
                'note' => clean($request->note),
                'status' => $request->status,
                'is_paid' => $isPaid,
            ]);

            $invoice->invoiceProducts->each->delete();
            // store invoice products
            foreach ($request->selectedProducts as $key => $selectedProduct) {
                $product = Product::where('slug', $selectedProduct['slug'])->first();
                $totalQty = $product->inventory_count + $selectedProduct['oldQty'] - $selectedProduct['qty'];
                // update product stock
                $product->update([
                    'inventory_count' => $totalQty,
                ]);

                InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $selectedProduct['id'],
                    'quantity' => $selectedProduct['qty'],
                    'purchase_price' => $selectedProduct['avgPurchasePrice'],
                    'sale_price' => $selectedProduct['unitPrice'],
                    'unit_cost' => $selectedProduct['unitCost'],
                    'tax_amount' => $selectedProduct['productTax'],
                ]);
            }

            return $this->responseWithSuccess('Invoice updated successfully', [
                'slug' => $invoice->slug,
            ]);
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        try {
            $invoice = Invoice::where('slug', $slug)->with('invoicePayments.invoicePaymentTransaction', 'invoiceProducts.product', 'invoiceReturn')->first();

            // delete return transaction
            $invoiceReturn = $invoice->invoiceReturn;
            if (isset($invoiceReturn)) {
                if ($invoiceReturn->transaction_id != null) {
                    $invoiceReturn->returnTransaction->delete();
                }
                // update product inventory count
                foreach ($invoiceReturn->invoiceReturnProducts as $invoiceReturnProduct) {
                    $product = $invoiceReturnProduct->product;
                    $product->update([
                        'inventory_count' => $product->inventory_count - $invoiceReturnProduct->quantity,
                    ]);
                }
                // delete return proucts
                $invoiceReturn->invoiceReturnProducts->each->delete();
            }

            $invoice->delete();

            return $this->responseWithSuccess('Invoice deleted successfully');
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * search resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(Request $request)
    {
        $term = $request->term;
        $query = Invoice::with('client', 'invoicePayments', 'user');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('invoice_date', [$request->startDate, $request->endDate]);
        }

        $query = $query->where(function ($query) use ($term) {
            $query->where('invoice_no', 'LIKE', '%' . $term . '%')
                ->where('reference', 'LIKE', '%' . $term . '%')
                ->orWhere('sub_total', 'LIKE', '%' . $term . '%')
                ->orWhere('po_reference', 'LIKE', '%' . $term . '%')
                ->orWhere('payment_terms', 'LIKE', '%' . $term . '%')
                ->orWhere('delivery_place', 'LIKE', '%' . $term . '%')
                ->orWhereHas('client', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%' . $term . '%')
                        ->orWhere('client_id', 'LIKE', '%' . $term . '%');
                });
        });

        return InvoiceListResource::collection($query->paginate($request->perPage));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allDueInvoices()
    {
        $dueInvoices = Invoice::where('status', 1)->where('is_paid', 0)->latest()->get();

        return InvoiceResource::collection($dueInvoices);
    }
}
