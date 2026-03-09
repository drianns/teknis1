<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatHeaderTicket;


class JourneyController extends Controller
{
    /**
     * Display the customer journey page.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $ticketNumber = $request->input('ticket_number');
        $ticketData = $request->all();
        $journey = collect([]);

        if ($ticketNumber) {
            $currentTicket = ChatHeaderTicket::where('ticket_number', $ticketNumber)->first();
            if ($currentTicket && $currentTicket->chat_header_id) {
                $journey = ChatHeaderTicket::where('chat_header_id', $currentTicket->chat_header_id)
                    ->with(['userAgent'])
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        }

        return view('pages.apps.journey.index', compact('ticketData', 'journey'));
    }
}
