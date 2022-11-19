<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DomainRequestStoreRequest;
use App\Http\Requests\DomainRequestUpdateRequest;
use App\Http\Resources\DomainRequestResource;
use App\Models\DomainRequest;
use App\Models\User;
use App\Notifications\NewDomainRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class DomainRequestController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $tenant = tenant();

        $domainRequests = tenancy()->central(function () use ($tenant, $request) {
            return DomainRequest::where('tenant_id', $tenant->id)
                ->orderBy('id', 'desc')
                ->paginate($request->perPage ?? 10);
        });

        return DomainRequestResource::collection($domainRequests);
    }

    /**
     * @param  \App\Http\Requests\DomainRequestStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(DomainRequestStoreRequest $request)
    {
        $tenant = tenant();

        $requestedDomain = DomainRequest::getDomain($request->requested_domain);

        try {
            $domainRequestMessage = tenancy()->central(function () use ($tenant, $requestedDomain) {
                $domainRequest = DomainRequest::updateOrCreate(
                    [
                        'requested_domain' => $requestedDomain,
                        'tenant_id' => $tenant->id,
                        'status' => DomainRequest::STATUS_PENDING,
                    ],
                    [
                        'requested_domain' => $requestedDomain,
                        'tenant_id' => $tenant->id,
                        'status' => DomainRequest::STATUS_PENDING,
                    ]);

                if ($domainRequest->wasRecentlyCreated) {
                    // notify admin
                    $admins = User::where('account_role', 1)->get();
                    Notification::send($admins, new NewDomainRequestNotification($requestedDomain, $tenant));

                    return [
                        'message' => 'Successfully requested domain',
                        'code' => 201,
                    ];
                } else {
                    return [
                        'message' => "Domain request already exists.",
                        'code' => 403,
                    ];
                }
            });

            return $this->responseWithSuccess(
                $domainRequestMessage['message'],
                [],
                $domainRequestMessage['code']
            );
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DomainRequest  $domainRequest
     * @return \App\Http\Resources\DomainRequestResource
     */
    public function show(Request $request, DomainRequest $domainRequest)
    {
        return new DomainRequestResource($domainRequest);
    }

    /**
     * @param  \App\Http\Requests\DomainRequestUpdateRequest  $request
     * @param  \App\Models\DomainRequest  $domainRequest
     * @return \App\Http\Resources\DomainRequestResource
     */
    public function update(DomainRequestUpdateRequest $request, DomainRequest $domainRequest)
    {
        $domainRequest->update($request->validated());

        return new DomainRequestResource($domainRequest);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $tenant = tenant();
        tenancy()->central(function () use ($id, $tenant) {
            $domainRequest = DomainRequest::findOrfail($id);
            if ($domainRequest && !$domainRequest->tenant_id == $tenant->id) {
                abort(403);
            }

            if (!$domainRequest->status == DomainRequest::STATUS_PENDING) {
                abort(403);
            }

            $domainRequest->delete();
        });

        return $this->responseWithSuccess('Domain request deleted successfully.');
    }
}
