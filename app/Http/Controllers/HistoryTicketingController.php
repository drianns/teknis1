<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryTicketingController extends Controller
{
    public function index()
    {
        $histories = \App\Models\ChatHeaderTicket::where('status', 'closed')->paginate(10);

        return view('pages.apps.history-ticketing.index', compact('histories'));
    }
}
