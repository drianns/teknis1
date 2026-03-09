<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryTicketingController extends Controller
{
    public function index()
    {
        return view('pages.apps.history-ticketing.index');
    }

    public function getData(Request $request)
    {
        $query = \App\Models\ChatHeaderTicket::where('status', 'closed')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 10);
        $data = $query->paginate($perPage);

        return response()->json($data);
    }
}
