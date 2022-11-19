<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferBalance\StoreTransferBalanceRequest;
use App\Http\Requests\TransferBalance\UpdateTransferBalanceRequest;
use App\Http\Resources\BalanceTranasferResource;
use App\Interfaces\ITransactionService;
use App\Models\AccountTransaction;
use App\Models\BalanceTansfer;
use Exception;
use Illuminate\Http\Request;

class TransferBalanceController extends Controller
{
    protected ITransactionService $transactionService;
    // define middleware
    public function __construct(ITransactionService $transactionService)
    {
        $this->middleware('can:account-transfer-balance-list', ['only' => ['index', 'search']]);
        $this->middleware('can:account-transfer-balance-create', ['only' => ['create']]);
        $this->middleware('can:account-transfer-balance-view', ['only' => ['show']]);
        $this->middleware('can:account-transfer-balance-edit', ['only' => ['update']]);
        $this->middleware('can:account-transfer-balance-delete', ['only' => ['destroy']]);

        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return BalanceTranasferResource::collection(BalanceTansfer::with('debitTransaction.cashbookAccount', 'creditTransaction.cashbookAccount', 'user')->latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransferBalanceRequest $request)
    {
        try {
            // get logged in user id
            $userId = auth()->user()->id;

            // store debit transaction
            $debitTransaction = $this->transactionService->createTransactionFromBalanceTransfer($request, $userId, 0);

            // store credit transaction
            $creditTransaction = $this->transactionService->createTransactionFromBalanceTransfer($request, $userId, 1);

            // create transfer
            BalanceTansfer::create([
                'reason' => $request->transferReason,
                'debit_id' => $debitTransaction->id,
                'credit_id' => $creditTransaction->id,
                'amount' => $request->amount,
                'date' => $request->date,
                'note' => clean($request->note),
                'status' => $request->status,
                'created_by' => $userId,
            ]);

            return $this->responseWithSuccess('Transfer added successfully');
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
            $transfer = BalanceTansfer::with('debitTransaction', 'creditTransaction', 'user')->where('slug', $slug)->first();

            return new BalanceTranasferResource($transfer);
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
    public function update(UpdateTransferBalanceRequest $request, $slug)
    {
        $transfer = BalanceTansfer::with('debitTransaction', 'creditTransaction', 'user')
                                    ->where('slug', $slug)
                                    ->first();

        try {
            // update debit transaction
            $transfer->debitTransaction->update([
                'account_id' => $request->fromAccount['id'],
                'amount' => $request->amount,
                'transaction_date' => $request->date,
                'status' => $request->status,
            ]);

            // update debit transaction
            $transfer->creditTransaction->update([
                'amount' => $request->amount,
                'transaction_date' => $request->date,
                'status' => $request->status,
            ]);

            // update transfer
            $transfer->update([
                'reason' => $request->transferReason,
                'amount' => $request->amount,
                'date' => $request->date,
                'note' => clean($request->note),
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Transfer updated successfully');
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
            $transfer = BalanceTansfer::where('slug', $slug)->first();

            // check if the transfer can be delete
            $canDelete = true;
            if ($transfer->creditTransaction->cashbookAccount->availableBalance() < $transfer->amount) {
                $canDelete = false;
            }

            if ($canDelete) {
                // delete transfer transactions
                $transfer->debitTransaction->delete();
                $transfer->creditTransaction->delete();
                $transfer->delete();
            } else {
                return $this->responseWithError('Sorry you can\'t delete this transfer!');
            }

            return $this->responseWithSuccess('Transfer deleted successfully');
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
        $query = BalanceTansfer::with('debitTransaction.cashbookAccount', 'creditTransaction.cashbookAccount', 'user');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('reason', 'LIKE', '%'.$term.'%')
                ->orWhere('amount', 'LIKE', '%'.$term.'%')
                ->orWhereHas('debitTransaction', function ($newQuery) use ($term) {
                    $newQuery->whereHas('cashbookAccount', function ($newQuery) use ($term) {
                        $newQuery->where('account_number', 'LIKE', '%'.$term.'%')
                            ->orWhere('bank_name', 'LIKE', '%'.$term.'%');
                    });
                })
                ->orWhereHas('creditTransaction', function ($newQuery) use ($term) {
                    $newQuery->whereHas('cashbookAccount', function ($newQuery) use ($term) {
                        $newQuery->where('account_number', 'LIKE', '%'.$term.'%')
                            ->orWhere('bank_name', 'LIKE', '%'.$term.'%');
                    });
                });
        });

        return BalanceTranasferResource::collection($query->latest()->paginate($request->perPage));
    }
}
