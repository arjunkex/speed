<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use Stancl\Tenancy\Database\Models\Domain;
use App\Http\Requests\TenantRegisterRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewSubscriptionNotification;
use App\Notifications\TenantRegisterNotifyForAdmin;
use App\Notifications\TenantVerificationNotification;

class TenantService
{
    use ApiResponse;

    /**
     * @param $request
     * @param $trialDayCount
     * @param $emailVerifiedAt
     * @return array
     */
    protected function tenantData($request, $trialDayCount, $emailVerifiedAt): array
    {
        return [
            'password' => bcrypt($request['password']),
            'ready' => false,
            // some other stuff, if you need. like cashier trials
            'trial_ends_at' => now()->addDays($trialDayCount),
            'trial_ends_email_sent_at' => null,
            'primary_domain_id' => null,
            'fallback_domain_id' => null,
            'is_banned' => false,
            'email_verified_at' => $emailVerifiedAt,
        ];
    }

    /**
     * Create Tenant and Domain
     * Then send email to user and get the domain with host.
     *
     * @param  TenantRegisterRequest  $request
     * @param  Carbon|null  $emailVerifiedAt
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTenantAndSendVerificationNotification(TenantRegisterRequest $request, Carbon $emailVerifiedAt = null): \Illuminate\Http\JsonResponse
    {
        $trialDayCount = GeneralSetting::where('key', 'trial_day_count')->first()?->value ?? 14;

        $tenant = Tenant::create(
            $request->safe()->except(['password', 'terms_and_conditions']) +
            $this->tenantData($request, $trialDayCount, $emailVerifiedAt),
        );

        // tenant verification mail
        $tenant->notify(new TenantVerificationNotification());

        return $this->responseWithSuccess(
            'Registration successful. Check your email for verification link.', $tenant
        );
    }

    /**
     * @param  Request  $request
     * @return array
     */
    public function createTenantAndDomainThenGetDomainWithHost(Request $request): array
    {
        $trialDayCount = GeneralSetting::where('key', 'trial_day_count')
                ->first()?->value ?? 14;

        $tenant = Tenant::create(
            $request->safe()->except('password') +
            $this->tenantData($request, $trialDayCount, now()),
        );

        $domain = $tenant->createDomain([
            'domain' => $request->domain,
        ]);

        $tenant->update([
            'ready' => true,
            'primary_domain_id' => $domain->id,
            'fallback_domain_id' => $domain->id,
        ]);

        // get host name
        $host = request()->getHttpHost();
        $domainWithHost = request()->getScheme() . '://' . $request->domain . '.' . $host;
        $token = tenancy()->impersonate(
            $tenant, 1, $request->domain . '.' . $host
        )->token;

        // notify tenant
        $tenant->notify(
            new NewSubscriptionNotification($domainWithHost, $request->password)
        );

        return [
            'domainWithHost' => $domainWithHost,
            'token' => $token,
        ];
    }

    /**
     * @param  Tenant  $tenant
     * @return array|void
     */
    public function createDomainAndLogin(Tenant $tenant)
    {
        $domain = $tenant->domain;

        $findDomain = Domain::where('domain', $domain)->first();

        if ($findDomain) {
            return;
        }

        $domain = $tenant->createDomain([
            'domain' => $domain,
        ]);

        $tenant->update([
            'ready' => true,
            'primary_domain_id' => $domain->id,
            'fallback_domain_id' => $domain->id,
        ]);

        // get host name
        [$host, $domainWithHost] = $this->getDomainWithHost($domain);
        $token = tenancy()->impersonate(
            $tenant, 1, $domain->domain . '.' . $host
        )->token;

        // notify tenant
        $tenant->notify(new NewSubscriptionNotification($domainWithHost));
        // notify admin
        $admins = User::where('account_role', 1)->get();
        Notification::send($admins , new TenantRegisterNotifyForAdmin($tenant, $domainWithHost));

        return [
            'domainWithHost' => $domainWithHost,
            'token' => $token,
        ];
    }

    /**
     * @param $domain
     * @return array
     */
    public function getDomainWithHost($domain): array
    {
        $host = request()->getHttpHost();
        $domainWithHost = request()->getScheme() . '://' . $domain->domain . '.' . $host;

        return [$host, $domainWithHost];
    }

    /**
     * @param  Tenant  $tenant
     * @return \Illuminate\Http\JsonResponse
     */
    public function impersonateAsTenant(Tenant $tenant)
    {
        $domain = $tenant->domain;

        $domain = Domain::where('domain', $domain)->first();

        // get host name
        [$host, $domainWithHost] = $this->getDomainWithHost($domain);
        $token = tenancy()->impersonate(
            $tenant, 1, $domain->domain . '.' . $host, 'web',
        )->token;

        return $this->responseWithSuccess(
            'Login successful.', [
                'redirect_url' => $domainWithHost.'/impersonate/'.$token
            ]
        );
    }
}
