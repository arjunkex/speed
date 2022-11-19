<?php

namespace App\Http\Controllers;

use App\Rules\FindDomainValidation;
use Illuminate\Http\Request;

class TenantDomainFindController extends Controller
{
    /*
    *   Find domain from database
    */
    public function findDomain(Request $request)
    {
        $request->validate([
            'domain' => ['required', 'string', 'max:255', 'alpha_dash', new FindDomainValidation()],
        ]);

        // get host name
        $host = request()->getHttpHost();
        $domainWithHost = $request->domain.'.'.$host;

        return $this->responseWithSuccess('Domain found', [
            'domain' => $domainWithHost,
        ]);
    }
}
