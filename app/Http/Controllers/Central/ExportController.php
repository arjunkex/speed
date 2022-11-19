<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function tenantsPdf()
    {
        $tenants = Tenant::latest()->get();
        view()->share('tenants', $tenants);
        $pdf = PDF::loadView('pdf.tenants');
        return $pdf->download('tenants-list.pdf');
    }
}