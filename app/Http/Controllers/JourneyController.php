<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JourneyController extends Controller
{
    /**
     * Display the customer journey page.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('pages.apps.journey.index', [
            'ticketData' => $request->all()
        ]);
    }
}
