<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Plan;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SpaController extends Controller
{
    /**
     * Get the SPA view.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function __invoke(Request $request)
    {
        if (tenant()->on_trial) {
            $tenantPlan = tenancy()->central(function () {
                return Plan::orderBy('amount')->first();
            });
        } else {
            $tenantPlan = tenant()->plan;
        }

        $tenantClientsCount = Client::count();
        $tenantSuppliersCount = Supplier::count();
        $tenantEmployeesCount = Employee::count();

        return view('tenant', compact('tenantPlan', 'tenantClientsCount', 'tenantSuppliersCount', 'tenantEmployeesCount'));
    }
}
