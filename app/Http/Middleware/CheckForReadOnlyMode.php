<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * Class CheckForReadOnlyMode.
 */
class CheckForReadOnlyMode
{
    /**
     * @param $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('lockout.enabled')) {
            // Check to see if this method and route is whitelisted
            foreach (config('lockout.whitelist') as $method => $routeNames) {
                if ($request->isMethod($method) && in_array($request->route()->getName(), $routeNames)) {
                    return $next($request);
                }
            }

            foreach (config('lockout.locked_types', []) as $type) {
                if ($request->isMethod('post') && config('lockout.allow_login')) {
                    abort_if(
                        $request->path() !== config('lockout.login_path') &&
                        $request->path() !== config('lockout.logout_path'),
                        499
                    );
                } elseif ($request->isMethod(strtolower($type))) {
                    abort(499);
                }
            }

            // Block any other specific get requests that may alter data
            if ($request->isMethod('get')) {
                collect(config('lockout.pages', []))
                    ->each(function ($item) use ($request) {
                        if ($request->path() === $item) {
                            abort(499);
                        }
                    });
            }
        }

        return $next($request);
    }
}
