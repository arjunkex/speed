<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\AdjustmentProduct;
use App\Models\InvoiceProduct;
use App\Models\InvoiceReturnProduct;
use App\Models\Product;
use App\Models\PurchaseProduct;
use App\Models\PurchaseReturnProduct;
use Exception;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:inventory-view', ['only' => ['allInventory']]);
        $this->middleware('can:inventory-history', ['only' => ['inventoryHistoryByItem']]);
    }

    // return product inventory
    public function allInventory(Request $request)
    {
        return ProductResource::collection(Product::with('proSubCategory.category', 'productUnit', 'productTax', 'productBrand')->orderBy('code', 'ASC')->paginate($request->perPage));
    }

    // return inventory history
    public function inventoryHistoryByItem($slug)
    {
        try {
            $product = Product::where('slug', $slug)->with('proSubCategory.category', 'productUnit')->first();
            // stock ins
            $purchaseIns = PurchaseProduct::where('product_id', $product->id)->with('purchase.supplier')->get();
            $invoiceReturnIns = InvoiceReturnProduct::where('product_id', $product->id)->with('invoiceReturn.invoice.client')->get();
            $adjutmentIns = AdjustmentProduct::where('product_id', $product->id)->where('type', 1)->with('inventoryAdjustment')->get();

            $stockIns = [];
            // Purchases
            foreach ($purchaseIns as $key => $inventoryIn) {
                $stockIns[$key]['code'] = config('config.purchasePrefix') . '-' . $inventoryIn->purchase->purchase_no;
                $stockIns[$key]['quantity'] = $inventoryIn->quantity;
                $stockIns[$key]['date'] = $inventoryIn->purchase->purchase_date;
                $stockIns[$key]['supplier'] = $inventoryIn->purchase->supplier->name;
                $stockIns[$key]['price'] = $inventoryIn->purchase_price;
                $stockIns[$key]['type'] = 'Purchase';
                $stockIns[$key]['purchaseNo'] = $inventoryIn->purchase->purchase_no;
            }

            $length = count($stockIns);
            // Invoice returns
            foreach ($invoiceReturnIns as $key => $inventoryIn) {
                $stockIns[$length]['code'] = config('config.invoiceReturnPrefix') . '-' . $inventoryIn->invoiceReturn->return_no;
                $stockIns[$length]['quantity'] = $inventoryIn->quantity;
                $stockIns[$length]['date'] = $inventoryIn->invoiceReturn->date;
                $stockIns[$length]['client'] = $inventoryIn->invoiceReturn->invoice->client->name;
                $stockIns[$length]['price'] = $product->purchase_price;
                $stockIns[$length]['type'] = 'Invoice Return';
                $stockIns[$length++]['invoiceNo'] = $inventoryIn->invoiceReturn->code;
            }

            $length = count($stockIns);
            // Inventory adjustments
            foreach ($adjutmentIns as $key => $inventoryIn) {
                $stockIns[$length]['code'] = config('config.adjustmentPrefix') . '-' . $inventoryIn->inventoryAdjustment->code;
                $stockIns[$length]['quantity'] = $inventoryIn->quantity;
                $stockIns[$length]['date'] = $inventoryIn->inventoryAdjustment->date;
                $stockIns[$length]['reason'] = $inventoryIn->inventoryAdjustment->reason;
                $stockIns[$length]['price'] = $inventoryIn->purchase_price;
                $stockIns[$length++]['type'] = 'Adjustment';
            }

            // stock outs
            $adjutmentOuts = AdjustmentProduct::where('product_id', $product->id)->where('type', 0)->with('inventoryAdjustment')->get();
            $inventoryOuts = InvoiceProduct::where('product_id', $product->id)->with('invoice.client')->get();
            $purchaseReturnOuts = PurchaseReturnProduct::where('product_id', $product->id)->with('purchaseReturn.purchase.supplier')->get();

            $stockOuts = [];
            // Invoice sales
            foreach ($inventoryOuts as $key => $inventoryOut) {
                $stockOuts[$key]['quantity'] = $inventoryOut->quantity;
                $stockOuts[$key]['code'] = config('config.invoicePrefix') . '-' . $inventoryOut->invoice->invoice_no;
                $stockOuts[$key]['date'] = $inventoryOut->invoice->invoice_date;
                $stockOuts[$key]['price'] = $inventoryOut->sale_price;
                $stockOuts[$key]['client'] = $inventoryOut->invoice->client->name;
                $stockOuts[$key]['type'] = 'Invoice';
            }

            $length = count($stockOuts);
            // Inventory adjustments
            foreach ($adjutmentOuts as $key => $adjutmentOut) {
                $stockOuts[$length]['quantity'] = $adjutmentOut->quantity;
                $stockOuts[$length]['date'] = $adjutmentOut->inventoryAdjustment->date;
                $stockOuts[$length]['reason'] = $adjutmentOut->inventoryAdjustment->reason;
                $stockOuts[$length]['code'] = config('config.adjustmentPrefix') . '-' . $adjutmentOut->inventoryAdjustment->code;
                $stockOuts[$length]['price'] = $adjutmentOut->purchase_price;
                $stockOuts[$length++]['type'] = 'Adjustment';
            }

            $length = count($stockOuts);
            // Purchase returns
            foreach ($purchaseReturnOuts as $key => $purchaseReturnOut) {
                $stockOuts[$length]['quantity'] = $purchaseReturnOut->quantity;
                $stockOuts[$length]['date'] = $purchaseReturnOut->purchaseReturn->date;
                $stockOuts[$length]['code'] = config('config.purchaseReturnPrefix') . '-' . $purchaseReturnOut->purchaseReturn->code;
                $stockOuts[$length]['supplier'] = $purchaseReturnOut->purchaseReturn->purchase->supplier->name;
                $stockOuts[$length]['reason'] = $purchaseReturnOut->purchaseReturn->reason;
                $stockOuts[$length]['price'] = $purchaseReturnOut->purchase_price;
                $stockOuts[$length++]['type'] = 'Purchase Return';
            }

            return [
                'product' => new ProductResource($product),
                'stockIns' => $stockIns,
                'stockOuts' => $stockOuts,
            ];
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }
}