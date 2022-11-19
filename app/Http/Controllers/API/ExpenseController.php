<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\StoreExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Interfaces\ITransactionService;
use App\Models\Expense;
use App\Services\ImageService;
use Exception;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    protected ITransactionService $transactionService;
    protected ImageService $imageService;

    // define middleware
    public function __construct(ITransactionService $transactionService)
    {
        $this->middleware('can:expense-list', ['only' => ['index', 'search']]);
        $this->middleware('can:expense-create', ['only' => ['create']]);
        $this->middleware('can:expense-view', ['only' => ['show']]);
        $this->middleware('can:expense-edit', ['only' => ['update']]);
        $this->middleware('can:expense-delete', ['only' => ['destroy']]);

        $this->imageService = new ImageService();
        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return ExpenseResource::collection(Expense::with('expSubCategory.expCategory', 'expTransaction.cashbookAccount',
            'user')->latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExpenseRequest $request)
    {

        try {
            // upload thumbnail and set the name
            $imageName = '';
            if ($request->image) {
                $imageName = $this->imageService->uploadImageAndGetPath($request->image, 'expenses');
            }

            $userId = auth()->user()->id;

            // store transaction
            $transaction = $this->transactionService->createTransactionFromExpense($request, $userId);

            // create expense
            Expense::create([
                'reason' => $request->reason,
                'sub_cat_id' => $request->subCategory['id'],
                'transaction_id' => $transaction->id,
                'date' => $request->date,
                'created_by' => $userId,
                'note' => clean($request->note),
                'image_path' => $imageName,
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Expense added successfully');
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
            $expense = Expense::with('expSubCategory', 'expTransaction.cashbookAccount', 'user')->where('slug',
                $slug)->first();

            return new ExpenseResource($expense);
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
    public function update(UpdateExpenseRequest $request, $slug)
    {
        $expense = Expense::with('expSubCategory', 'expTransaction.cashbookAccount')->where('slug', $slug)->first();

        try {
            $imageName = $expense->image_path;
            if ($request->image) {
                $imageName = $this->imageService->uploadImageAndGetPath($request->image, 'expenses');
                $this->imageService->checkImageExistsAndDelete($expense->image_path,'expenses');
            }

            // update transaction
            $transaction = $expense->expTransaction;
            $transaction->update([
                'account_id' => $request->account['id'],
                'amount' => $request->amount,
                'transaction_date' => $request->date,
                'status' => $request->status,
                'cheque_no' => $request->chequeNo,
                'receipt_no' => $request->voucherNo,
            ]);

            // update expense
            $expense->update([
                'reason' => $request->reason,
                'sub_cat_id' => $request->subCategory['id'],
                'transaction_id' => $transaction->id,
                'date' => $request->date,
                'note' => clean($request->note),
                'image_path' => $imageName,
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Expense updated successfully');
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
            $expense = Expense::where('slug', $slug)->first();
            $this->imageService->checkImageExistsAndDelete($expense->image_path, 'expenses');
            $expense->delete();

            return $this->responseWithSuccess('Expense deleted successfully');
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * search resource from storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(Request $request)
    {
        $term = $request->term;
        $query = Expense::with('expSubCategory.expCategory', 'expTransaction.cashbookAccount', 'user');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('reason', 'LIKE', '%'.$term.'%')
                ->orWhereHas('expSubCategory', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%'.$term.'%')
                        ->orWhereHas('expCategory', function ($newQuery) use ($term) {
                            $newQuery->where('name', 'LIKE', '%'.$term.'%');
                        });
                })
                ->orWhereHas('expTransaction', function ($newQuery) use ($term) {
                    $newQuery->where('amount', 'LIKE', '%'.$term.'%')
                        ->orWhereHas('cashbookAccount', function ($newQuery) use ($term) {
                            $newQuery->where('account_number', 'LIKE', '%'.$term.'%');
                        });
                });
        });

        return ExpenseResource::collection($query->latest()->paginate($request->perPage));
    }
}
