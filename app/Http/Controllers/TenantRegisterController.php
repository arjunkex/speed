<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantRegisterRequest;
use App\Services\TenantService;

class TenantRegisterController extends Controller
{
    /*
    *   Store data
    */
    public function store(TenantRegisterRequest $request, TenantService $tenantService)
    {
        return $tenantService->createTenantAndSendVerificationNotification($request);
    }
}
