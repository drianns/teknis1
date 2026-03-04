<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketingDepartmentController extends Controller
{
    public function index(Request $request)
    {
        $tickets = \App\Models\ChatHeaderTicket::where('status', 'open')->paginate(10);

        return view('pages.apps.ticketing-department.index', compact('tickets'));
    }
}
