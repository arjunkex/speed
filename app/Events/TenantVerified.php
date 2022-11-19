<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Stancl\Tenancy\Contracts\Tenant;

class TenantVerified
{
    use Dispatchable, SerializesModels;

    /** @var Tenant */
    public $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }
}
