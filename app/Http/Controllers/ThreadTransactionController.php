<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatHeader;
use App\Models\ChatHeaderTicket;

class ThreadTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = ChatHeader::with(['channel', 'latestTicket.userAgent.user']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $query->orderByDesc('created_at');

        $baseQuery    = clone $query;
        $closedCount  = (clone $baseQuery)->where('status', 'like', '%close%')->count();
        $openCount    = (clone $baseQuery)->where('status', 'like', '%open%')->count();
        $totalTickets = ChatHeaderTicket::when($request->filled('start_date'), fn($q) =>
                $q->whereDate('created_at', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn($q) =>
                $q->whereDate('created_at', '<=', $request->end_date))
            ->count();

        $perPage = (int) $request->get('per_page', 10);
        $threads  = $query->paginate($perPage)->withQueryString();

        return view('pages.apps.thread-transaction.index', compact(
            'threads', 'closedCount', 'openCount', 'totalTickets'
        ));
    }

    public function getData(Request $request)
    {
        // Existing getData logic if needed, but the index now handles pagination
        $query = ChatHeaderTicket::orderBy('created_at', 'desc');

        if ($request->has('channel') && $request->channel != '') {
            $query->where('source_type', $request->channel);
        }

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
