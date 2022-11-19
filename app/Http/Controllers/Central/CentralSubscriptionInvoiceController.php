<?php

namespace App\Http\Controllers\Central;

use App\Models\Tenant;
use App\Http\Controllers\Controller;
use App\Http\Requests\CentralSubscriptionInvoiceDownloadRequest;


class CentralSubscriptionInvoiceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  CentralSubscriptionInvoiceDownloadRequest  $request
     *
     * @return mixed
     */
    public function store(CentralSubscriptionInvoiceDownloadRequest $request)
    {
        $tenant = Tenant::findOrFail($request->tenant_id);

        return $tenant->downloadInvoice($request->invoice_id, [
            'vendor' => $request->vendor_name,
            'product' => $request->product_name,
        ]);
    }
}