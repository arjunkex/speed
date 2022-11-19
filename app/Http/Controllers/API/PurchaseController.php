<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Purchase\StorePurchaseRequest;
use App\Http\Requests\Purchase\UpdatePurchaseRequest;
use App\Http\Resources\PurchaseListReource;
use App\Http\Resources\PurchaseProductsResource;
use App\Interfaces\ITransactionService;
use App\Models\AccountTransaction;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\PurchaseProduct;
use App\Rules\MinTotal;
use App\Rules\PurchaseTotalPaid;
use Exception;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    protected ITransactionService $transactionService;
    // define middleware
    public function __construct(ITransactionService $transactionService)
    {
        $this->middleware('can:purchase-list', ['only' => ['index', 'search']]);
        $this->middleware('can:purchase-create', ['only' => ['create']]);
        $this->middleware('can:purchase-view', ['only' => ['show']]);
        $this->middleware('can:purchase-edit', ['only' => ['update']]);
        $this->middleware('can:purchase-delete', ['only' => ['destroy']]);

        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return PurchaseListReource::collection(Purchase::with('supplier', 'purchasePayments', 'purchaseTax')->latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePurchaseRequest $request)
    {
        $this->checkSubscriptionLimitByModelName('Purchase');

        try {
            // generate code
            $code = 1;
            $prevCode = Purchase::latest()->first();
            if ($prevCode) {
                $code = $prevCode->purchase_no + 1;
            }

            // get logged in user id
            $userId = auth()->user()->id;

            // create purchase
            $purchase = Purchase::create([
                'purchase_no' => $code,
                'slug' => uniqid(),
                'supplier_id' => $request->supplier['id'],
                'discount' => $request->discount,
                'transport' => $request->transportCost,
                'tax_id' => $request->orderTax['id'],
                'sub_total' => $request->subTotal,
                'po_reference' => $request->poReference,
                'payment_terms' => $request->paymentTerms,
                'po_date' => $request->poDate,
                'purchase_date' => $request->purchaseDate,
                'note' => clean($request->note),
                'status' => $request->status,
                'created_by' => $userId,
            ]);

            // store purchase products
            foreach ($request->selectedProducts as $key => $selectedProduct) {
                $product = Product::where('slug', $selectedProduct['slug'])->first();

                // calculate new purchase price
                $currentStockPrice = $product->inventory_count * $product->purchase_price;
                $newStockPrice = $selectedProduct['qty'] * $selectedProduct['unitCost'];
                $totalStockPrice = $currentStockPrice + $newStockPrice;
                $totalQty = $product->inventory_count + $selectedProduct['qty'];
                $unitCost = $totalStockPrice / $totalQty;

                // update product stock purchase price
                $product->update([
                    'purchase_price' => $unitCost,
                    'inventory_count' => $product->inventory_count + $selectedProduct['qty'],
                ]);

                PurchaseProduct::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $selectedProduct['qty'],
                    'purchase_price' => $selectedProduct['unitPrice'],
                    'unit_cost' => $selectedProduct['unitCost'],
                    'tax_amount' => $selectedProduct['productTax'],
                ]);
            }

            // store transaction
            if ($request->addPayment == true) {

                // create transaction
                $transaction = $this->transactionService->createTransactionFromPayment($request, $userId, $purchase);

                // store purchase payment record
                PurchasePayment::create([
                    'slug' => uniqid(),
                    'purchase_id' => $purchase->id,
                    'transaction_id' => $transaction->id,
                    'amount' => $request->totalPaid,
                    'date' => $request->purchaseDate,
                    'note' => clean($request->note),
                    'created_by' => $userId,
                    'status' => $request->status,
                ]);
            }
            // update purchase
            if ($purchase->totalDue() == 0) {
                $purchase->update([
                    'is_paid' => 1,
                ]);
            }

            return $this->responseWithSuccess('Purchase added successfully', [
                'slug' => $purchase->slug,
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
            $purchase = Purchase::with('supplier', 'purchaseProducts.purchase', 'purchaseReturn', 'purchasePayments.purchasePaymentTransaction.cashbookAccount', 'purchaseProducts.product.productUnit', 'purchaseProducts.product.productTax', 'purchaseProducts.product.proSubCategory.category', 'user')->where('slug', $slug)->first();

            return new PurchaseProductsResource($purchase);
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
    public function update(UpdatePurchaseRequest $request, $slug)
    {
        $purchase = Purchase::where('slug', $slug)->with('purchaseProducts.product')->first();

        try {
            // delete current products
            $purchase->purchaseProducts->each->delete();
            // store purchase products
            foreach ($request->selectedProducts as $key => $selectedProduct) {
                $product = Product::where('slug', $selectedProduct['slug'])->first();

                // calculate new purchase price
                $currentStockPrice = $product->inventory_count * $product->purchase_price;
                $newStockPrice = $selectedProduct['qty'] * $selectedProduct['unitCost'];
                $totalStockPrice = $currentStockPrice + $newStockPrice;
                $totalQty = $product->inventory_count + $selectedProduct['qty'];
                $unitCost = $totalStockPrice / $totalQty;

                $newInventory = $product->inventory_count - $selectedProduct['oldQty'] + $selectedProduct['qty'];

                // update product purchase price
                $product->update([
                    'purchase_price' => $unitCost,
                    'inventory_count' => $newInventory,
                ]);

                // store products
                PurchaseProduct::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $selectedProduct['qty'],
                    'purchase_price' => $selectedProduct['unitPrice'],
                    'unit_cost' => $selectedProduct['unitCost'],
                    'tax_amount' => $selectedProduct['productTax'],
                ]);
            }

            // update purchase
            $purchase->update([
                'supplier_id' => $request->supplier['id'],
                'discount' => $request->discount,
                'transport' => $request->transportCost,
                'tax_id' => $request->orderTax['id'],
                'sub_total' => $request->rowSubTotal,
                'po_reference' => $request->poReference,
                'payment_terms' => $request->paymentTerms,
                'po_date' => $request->poDate,
                'purchase_date' => $request->purchaseDate,
                'note' => clean($request->note),
                'status' => $request->status,
                'is_paid' => 1,
            ]);

            return $this->responseWithSuccess('Purchase updated successfully', [
                'slug' => $purchase->slug,
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
            $purchase = Purchase::where('slug', $slug)->with('purchasePayments.purchasePaymentTransaction', 'purchaseProducts.product.productUnit', 'purchaseReturn.purchaseReturnProducts', 'purchaseReturn.returnTransaction')->first();

            // delete purchase return
            $purchaseReturn = $purchase->purchaseReturn;
            if (isset($purchaseReturn)) {
                if ($purchaseReturn->transaction_id != null) {
                    $purchaseReturn->returnTransaction->delete();
                }
                // update product inventory count
                foreach ($purchaseReturn->purchaseReturnProducts as $purchaseReturnProduct) {
                    $product = $purchaseReturnProduct->product;
                    $product->update([
                        'inventory_count' => $product->inventory_count + $purchaseReturnProduct->quantity,
                    ]);
                }
                // delete return proucts
                $purchaseReturn->purchaseReturnProducts->each->delete();
            }

            // delete purchase
            $purchase->delete();

            return $this->responseWithSuccess('Purchase deleted successfully!');
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
        $query = Purchase::with('supplier', 'purchasePayments', 'user');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('purchase_date', [$request->startDate, $request->endDate]);
        }

        $query = $query->where(function ($query) use ($term) {
            $query->where('purchase_no', 'LIKE', '%'.$term.'%')
                ->orWhere('sub_total', 'LIKE', '%'.$term.'%')
                ->orWhere('transport', 'LIKE', '%'.$term.'%')
                ->orWhere('discount', 'LIKE', '%'.$term.'%')
                ->orWhere('po_reference', 'LIKE', '%'.$term.'%')
                ->orWhere('payment_terms', 'LIKE', '%'.$term.'%')
                ->orWhereHas('supplier', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%'.$term.'%')
                        ->orWhere('company_name', 'LIKE', '%'.$term.'%')
                        ->orWhere('phone', 'LIKE', '%'.$term.'%');
                });
        });

        return PurchaseListReource::collection($query->latest()->paginate($request->perPage));
    }
}
