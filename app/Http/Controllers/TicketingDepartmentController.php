<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketingDepartmentController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.apps.ticketing-department.index');
    }

    public function getData(Request $request)
    {
        $query = \App\Models\ChatHeaderTicket::where('status', 'open');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
        }

        $perPage = $request->get('per_page', 10);
        $data = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($data);
    }
}
