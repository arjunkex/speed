<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Stancl\Tenancy\Features\UserImpersonation;

class TenantImpersonationController extends Controller
{
    public function impersonate($token)
    {
        $impersonate = UserImpersonation::makeResponse($token);

        if ($impersonate) {
            $admins = tenancy()->central(function () {
                return User::all();
            });

            $user = User::first();

            $token = (string) $user->createToken(Str::random(10))->plainTextToken;

            return response()->json([
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => null,
            ]);

            // // send mail to admin
            // Notification::send($admins , new VendorRegistation($user, tenant(), tenant()->id));

            // // send thank you mail to vendors
            // $user->notify(new WelcomeVendor(tenant()->domains[0]->domain, url('/')));

            // return true;
        }

        return false;
    }
}
