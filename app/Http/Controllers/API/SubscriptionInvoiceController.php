<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionInvoiceDownloadRequest;
use App\Http\Resources\SubscriptionInvoiceResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SubscriptionInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return SubscriptionInvoiceResource::collection(tenant()->invoices());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SubscriptionInvoiceDownloadRequest  $request
     * @return mixed
     */
    public function store(SubscriptionInvoiceDownloadRequest $request)
    {
        return tenant()->downloadInvoice($request->invoice_id, [
            'vendor' => $request->vendor_name,
            'product' => $request->product_name,
        ]);
    }
}
