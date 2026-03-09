<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InboxEmailController extends Controller
{
    public function index()
    {
        $inbox = collect();
        $drafts = collect();
        $spam = collect();
        return view('pages.channel.email.inbox.index', compact('inbox', 'drafts', 'spam'));
    }

    public function getInboxData(Request $request)
    {
        $query = \App\Models\ChatHeaderTicket::where('source_type', 'email')
            ->whereIn('status', ['open', 'in_progress']); // example definition of inbox

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
        }

        return response()->json($query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 10)));
    }

    public function getDraftsData(Request $request)
    {
        $query = \App\Models\ChatHeaderTicket::where('source_type', 'email')
            ->where('status', 'pending'); // example definition of draft

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
        }

        return response()->json($query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 10)));
    }

    public function getSpamData(Request $request)
    {
        $query = \App\Models\ChatHeaderTicket::where('source_type', 'email')
            ->where('status', 'spam'); // example definition of spam

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
        }

        return response()->json($query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 10)));
    }

    public function markAsRead($id)
    {
        // In a real application, you would update the database here.
        // For example: Email::find($id)->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Email marked as read'
        ]);
    }

    public function moveToSpam($id)
    {
        // In a real application, you would update the database here.
        // For example: Email::find($id)->update(['folder' => 'spam']);

        return response()->json([
            'status' => 'success',
            'message' => 'Email moved to spam'
        ]);
    }
}
