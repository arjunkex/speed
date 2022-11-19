<?php

namespace App\Http\Controllers;

class CentralAppController extends Controller
{
    /**
     * Get the SPA view.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function __invoke()
    {
        return view('central');
    }
}
