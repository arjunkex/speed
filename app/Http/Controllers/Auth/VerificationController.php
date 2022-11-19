<?php

namespace App\Http\Controllers\Auth;

use App\Events\TenantVerified;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\TenantVerificationNotification;
use App\Services\TenantService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Tenant  $tenant
     * @param  TenantService  $tenantService
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function verify(Request $request, Tenant $tenant, TenantService $tenantService)
    {
        if (! URL::hasValidSignature($request)) {
            return view('central.tenant.auth.register', [
                'success' => false,
                'message' => trans('verification.invalid'),
            ]);
        }

        [, $domainWithHost] = $tenantService->getDomainWithHost($tenant);

        if ($tenant->hasVerifiedEmail()) {
            return view('central.tenant.auth.register', [
                'success' => true,
                'message' => trans('verification.already_verified'),
                'domain' => $domainWithHost,
            ]);
        }

        $domainWithHostAndToken = $tenantService->createDomainAndLogin($tenant);

        if (! $domainWithHostAndToken) {
            return view('central.tenant.auth.register', [
                'success' => true,
                'message' => trans('verification.already_verified'),
                'domain' => $domainWithHost,
            ]);
        }

        $tenant->markEmailAsVerified();

        TenantVerified::dispatch($tenant);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => trans('verification.verified'),
            ]);
        }

        return redirect($domainWithHostAndToken['domainWithHost'].'/impersonate/'.$domainWithHostAndToken['token'])->with([
            'message' => trans('verification.verified'),
        ]);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        $this->validate($request, ['email' => ['required', 'email']]);

        $tenant = Tenant::where('data->email', $request->email)->first();

        if (is_null($tenant)) {
            throw ValidationException::withMessages([
                'email' => [trans('verification.user')],
            ]);
        }

        if ($tenant->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => [trans('verification.already_verified')],
            ]);
        }

        $tenant->notify(new TenantVerificationNotification());

        return response()->json(['message' => trans('verification.sent')]);
    }
}
