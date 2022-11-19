<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Rules\DomainValidation;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function index()
    {
        $tenant = tenant();
        $domains = $tenant->domains;

        foreach ($domains as $domain) {
            $domain['is_primary'] = $tenant->primary_domain_id == $domain->id;
            $domain['is_fallback'] = $tenant->fallback_domain_id == $domain->id;
            $domain['is_domain'] = str_contains($domain->domain, '.');
        }

        // sort domains by primary
        $domains = $domains->sortByDesc('is_primary')->values();

        return $this->responseWithSuccess(
            'Domains retrieved successfully!',
            $domains
        );
    }

    /**
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $this->checkSubscriptionLimitByModelName('Domain');

        $request->validate([
            'domain' => ['required', 'string', 'regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i', new DomainValidation()],
        ]);

        $domain = tenant()->createDomain([
            'domain' => $request->domain,
        ]);

        return $this->responseWithSuccess('Domain created successfully.', compact('domain'));
    }

    public function makePrimary($domainId)
    {
        $domain = tenant()->domains()->findOrFail($domainId);

        if ($domain) {
            tenant()->update([
                'primary_domain_id' => $domain->id,
            ]);
        }

        return $this->responseWithSuccess('Domain status updated successfully.');
    }

    public function delete($domainId)
    {
        tenant()->domains()->findOrFail($domainId)->delete();

        return $this->responseWithSuccess('Domain deleted successfully.');
    }
}
