<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function ip()
    {
        return $this->responseWithSuccess('Server IP', [
            'server_ip' => request()->server('SERVER_ADDR'),
        ]);
    }
}
