<?php

namespace App\Http\Controllers;

use App\Models\ChannelTicket;
use Illuminate\Http\Request;

class ChannelTicketController extends Controller
{
    public function index()
    {
        $items = ChannelTicket::latest()->paginate(15);
        return view('pages.master-data.channel-ticket.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        ChannelTicket::create($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Channel Ticket created successfully.');
    }

    public function update(Request $request, ChannelTicket $record)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Channel Ticket updated successfully.');
    }

    public function destroy(ChannelTicket $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Channel Ticket deleted successfully.');
    }
}