<?php

namespace App\Http\Controllers\Central;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DomainResource;
use Stancl\Tenancy\Database\Models\Domain;

class DomainController extends Controller
{
    public function index(Request $request)
    {
        $domains = Domain::with('tenant');
        return DomainResource::collection($domains->latest()->paginate($request->perPage ?? 10));
    }

    public function destroy(Domain $domain)
    {
        $domain->delete();
        return $this->responseWithSuccess('Domain deleted successfully.');
    }
}