<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\NonPurchasePayment\StoreNonPurchasePaymentRequest;
use App\Http\Requests\NonPurchasePayment\UpdateNonPurchasePaymentRequest;
use App\Http\Resources\NonPurchasePaymentListResource;
use App\Http\Resources\NonPurchasePaymentResource;
use App\Interfaces\ITransactionService;
use App\Models\NonPurchasePayment;
use Exception;
use Illuminate\Http\Request;

class NonPurchasePaymentController extends Controller
{

    protected ITransactionService $transactionService;

    // define middleware
    public function __construct(ITransactionService $transactionService)
    {
        $this->middleware('can:non-purchase-payment-list', ['only' => ['index', 'search']]);
        $this->middleware('can:non-purchase-payment-create', ['only' => ['create']]);
        $this->middleware('can:non-purchase-payment-view', ['only' => ['show']]);
        $this->middleware('can:non-purchase-payment-edit', ['only' => ['update']]);
        $this->middleware('can:non-purchase-payment-delete', ['only' => ['destroy']]);

        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return NonPurchasePaymentListResource::collection(NonPurchasePayment::with('supplier', 'paymentTransaction.cashbookAccount')->latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNonPurchasePaymentRequest $request)
    {

        try {
            $userId = auth()->user()->id;

            if ($request->type == 1) {

                $transaction = $this->transactionService->createTransactionFromNonPurchasePayment($request, $userId);
            }

            // store payment
            NonPurchasePayment::create([
                'slug' => uniqid(),
                'supplier_id' => $request->supplier['id'],
                'amount' => $request->amount,
                'type' => $request->type,
                'transaction_id' => isset($transaction) ? $transaction->id : null,
                'date' => $request->paymentDate,
                'note' => $request->note,
                'status' => $request->status,
                'created_by' => $userId,
            ]);

            return $this->responseWithSuccess('Non purchase payment added successfully');
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
            $payment = NonPurchasePayment::where('slug', $slug)->first();

            return new NonPurchasePaymentResource($payment);
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreNonPurchasePaymentRequest $request, $slug)
    {
        $payment = NonPurchasePayment::where('slug', $slug)->first();

        try {
            $payment->update([
                'amount' => $request->amount,
                'date' => $request->paymentDate,
                'note' => $request->note,
                'status' => $request->status,
            ]);

            if ($request->type == 1) {
                // update transaction
                $payment->paymentTransaction->update([
                    'account_id' => $request->account['id'],
                    'amount' => $request->amount,
                    'cheque_no' => $request->chequeNo,
                    'receipt_no' => $request->receiptNo,
                    'type' => 0,
                    'transaction_date' => $request->paymentDate,
                    'status' => $request->status,
                ]);
            }

            return $this->responseWithSuccess('Payment updated successfully');
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
            $payment = NonPurchasePayment::with('supplier')->where('slug', $slug)->first();

            // check if the payment can be delete
            $canDelete = true;
            if ($payment->type == 0) {
                if ($payment->amount > $payment->supplier->nonPurchaseCurrentDue()) {
                    $canDelete = false;
                }
            }

            if ($canDelete) {
                if ($payment->type == 1) {
                    $payment->paymentTransaction->delete();
                }
                $payment->delete();
            } else {
                return $this->responseWithError('Sorry you can\'t delete this invoice!');
            }

            return $this->responseWithSuccess('Payment deleted successfully');
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
        $query = NonPurchasePayment::with('supplier', 'paymentTransaction.cashbookAccount');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('amount', '=', $term)
                ->orWhereHas('supplier', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%'.$term.'%')
                        ->orWhere('email', 'LIKE', '%'.$term.'%')
                        ->orWhere('company_name', 'LIKE', '%'.$term.'%')
                        ->orWhere('phone', 'LIKE', '%'.$term.'%');
                })
                ->orWhereHas('paymentTransaction', function ($newQuery) use ($term) {
                    $newQuery->where('cheque_no', 'Like', '%'.$term.'%')->orWhere('receipt_no', 'Like', '%'.$term.'%')->whereHas('cashbookAccount', function ($newQuery) use ($term) {
                        $newQuery->where('account_number', 'LIKE', '%'.$term.'%')
                            ->orWhere('bank_name', 'LIKE', '%'.$term.'%');
                    });
                });
        });

        return NonPurchasePaymentListResource::collection($query->latest()->paginate($request->perPage));
    }
}
