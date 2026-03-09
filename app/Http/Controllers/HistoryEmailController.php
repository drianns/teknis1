<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryEmailController extends Controller
{
    public function index()
    {
        return view('pages.channel.email.history.index');
    }

    public function getData(Request $request)
    {
        $query = \App\Models\ChatHeaderTicket::where('source_type', 'email')
            ->whereIn('status', ['closed', 'resolved']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
        }

        return response()->json($query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 10)));
    }
}
