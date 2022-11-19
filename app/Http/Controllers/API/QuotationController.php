<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quotation\StoreQuotationRequest;
use App\Http\Requests\Quotation\UpdateQuotationRequest;
use App\Http\Resources\QuotationListResource;
use App\Http\Resources\QuotationResource;
use App\Models\Quotation;
use App\Models\QuotationProduct;
use Exception;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:quotation-list', ['only' => ['index', 'search']]);
        $this->middleware('can:quotation-create', ['only' => ['create']]);
        $this->middleware('can:quotation-view', ['only' => ['show']]);
        $this->middleware('can:quotation-edit', ['only' => ['update']]);
        $this->middleware('can:quotation-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return QuotationListResource::collection(Quotation::with('client', 'user')->latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreQuotationRequest $request)
    {

        try {
            // generate code
            $code = 1;
            $lastQuotation = Quotation::latest()->first();
            if ($lastQuotation) {
                $code = $lastQuotation->quotation_no + 1;
            }

            // calculate discount
            $discount = $request->discount;
            if ($request->discountType == 1) {
                $discount = $request->totalDiscount;
            }

            // create quotation
            $quotation = Quotation::create([
                'quotation_no' => $code,
                'slug' => uniqid(),
                'reference' => $request->reference,
                'client_id' => $request->client['id'],
                'transport' => $request->transportCost,
                'discount_type' => $request->discountType,
                'discount' => $discount,
                'total_tax' => $request->totalTax,
                'sub_total' => $request->subTotal,
                'delivery_place' => $request->deliveryPlace,
                'tax_id' => $request->orderTax['id'],
                'quotation_date' => $request->date,
                'note' => clean($request->note),
                'status' => $request->status,
                'created_by' => auth()->user()->id,
            ]);

            // store quotation products
            foreach ($request->selectedProducts as $key => $selectedProduct) {
                QuotationProduct::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $selectedProduct['id'],
                    'quantity' => $selectedProduct['qty'],
                    'purchase_price' => $selectedProduct['avgPurchasePrice'],
                    'sale_price' => $selectedProduct['unitPrice'],
                    'unit_cost' => $selectedProduct['unitCost'],
                    'tax_amount' => $selectedProduct['totalTax'],
                ]);
            }

            return $this->responseWithSuccess('Quotation added successfully', [
                'slug' => $quotation->slug,
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
            $quotation = Quotation::with('client', 'quotationProducts.product.productUnit', 'quotationProducts.product.productTax', 'user')->where('slug', $slug)->first();

            return new QuotationResource($quotation);
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
    public function update(UpdateQuotationRequest $request, $slug)
    {
        $quotation = Quotation::where('slug', $slug)->first();

        try {
            // calculate discount
            $discount = $request->discount;
            if ($request->discountType == 1) {
                $discount = $request->totalDiscount;
            }

            // update quotation
            $quotation->update([
                'reference' => $request->reference,
                'client_id' => $request->client['id'],
                'transport' => $request->transportCost,
                'discount_type' => $request->discountType,
                'discount' => $discount,
                'total_tax' => $request->totalTax,
                'sub_total' => $request->subTotal,
                'delivery_place' => $request->deliveryPlace,
                'tax_id' => $request->orderTax['id'],
                'quotation_date' => $request->date,
                'note' => clean($request->note),
                'status' => $request->status,
            ]);

            // delete old products and store new products
            $quotation->quotationProducts->each->delete();
            foreach ($request->selectedProducts as $key => $selectedProduct) {
                QuotationProduct::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $selectedProduct['id'],
                    'quantity' => $selectedProduct['qty'],
                    'purchase_price' => $selectedProduct['avgPurchasePrice'],
                    'sale_price' => $selectedProduct['unitPrice'],
                    'unit_cost' => $selectedProduct['unitCost'],
                    'tax_amount' => $selectedProduct['totalTax'],
                ]);
            }

            return $this->responseWithSuccess('Quotation updated successfully', [
                'slug' => $quotation->slug,
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
            $quotation = Quotation::where('slug', $slug)->first();
            $quotation->quotationProducts->each->delete();
            $quotation->delete();

            return $this->responseWithSuccess('Quotation deleted successfully');
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
        $query = Quotation::with('client', 'user');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('quotation_date', [$request->startDate, $request->endDate]);
        }

        $query = $query->where(function ($query) use ($term) {
            $query->where('quotation_no', 'LIKE', '%'.$term.'%')
                ->orWhere('reference', 'LIKE', '%'.$term.'%')
                ->orWhere('delivery_place', 'LIKE', '%'.$term.'%')
                ->orWhere('discount', 'LIKE', '%'.$term.'%')
                ->orWhere('total_tax', 'LIKE', '%'.$term.'%')
                ->orWhere('sub_total', 'LIKE', '%'.$term.'%')
                ->orWhereHas('client', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%'.$term.'%')
                        ->orWhere('email', 'LIKE', '%'.$term.'%')
                        ->orWhere('company_name', 'LIKE', '%'.$term.'%')
                        ->orWhere('phone', 'LIKE', '%'.$term.'%');
                });
        });

        return QuotationListResource::collection($query->latest()->paginate($request->perPage));
    }
}
