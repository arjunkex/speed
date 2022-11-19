<?php

namespace App\Http\Controllers\Central;

use App\Models\Tenant;
use App\Http\Controllers\Controller;
use App\Jobs\SendNotificationToTenantJob;
use App\Http\Requests\SendNotificationToTenantRequest;

class SendNotificationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(SendNotificationToTenantRequest $request, Tenant $tenant)
    {
        SendNotificationToTenantJob::dispatch($tenant, $request->subject, $request->greeting, $request->body);
        return $this->responseWithSuccess('Notification sent successfully');
    }
}