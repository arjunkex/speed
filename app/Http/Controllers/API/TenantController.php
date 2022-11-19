<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\TenantResource;
use App\Models\User;

class TenantController extends Controller
{
    public function me()
    {
        $tenant = tenant();
        if ($tenant->manually_subscribed_by) {
            $tenant->manually_subscribed_by = tenancy()->central(function () use ($tenant) {
                return User::find($tenant->manually_subscribed_by)?->name ?? null;
            });
        }

        return $this->responseWithSuccess('Tenant data retrieved successfully', new TenantResource(tenant()));
    }
}
