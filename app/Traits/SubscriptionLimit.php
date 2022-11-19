<?php

namespace App\Traits;

use App\Models\Plan;
use Illuminate\Support\Str;

trait SubscriptionLimit
{
    /**
     * @throws \Exception
     */
    public function checkSubscriptionLimitByModelName($modelName): void
    {
        if (empty($modelName)) {
            throw new \Exception('Model name can\'t be empty for checkSubscriptionLimitByModelName method');
        }
        if (tenant()->on_trial) {
            $tenantPlan = tenancy()->central(function () {
                return Plan::orderBy('amount')->first();
            });
        } else {
            $tenantPlan = tenant()->plan;
        }

        $limitColumnName = 'limit_'.Str::snake($modelName).'s';

        $tenantLimit = $tenantPlan?->$limitColumnName;

        if ($modelName == 'Domain') {
            $tenantCurrentCount = tenant()->domains()->count();
        } else {
            $modelNameWithNamespace = '\App\Models\\'.$modelName;

            $tenantCurrentCount = $modelNameWithNamespace::count();
        }

        if ($tenantLimit <= $tenantCurrentCount && $tenantLimit > 0) {
            response()->json([
                'limit' => $tenantLimit,
                'used' => $tenantCurrentCount,
                'error' => true,
                'message' => 'You have exceeded the subscription limit, please upgrade your subscription.',
                'data' => [],
            ], 403)->throwResponse();
        }
    }
}
