<?php

namespace App\Http\Controllers;

use App\Models\ChannelTicket;
use Illuminate\Http\Request;

class ChannelTicketController extends Controller
{
    public function index()
    {
        return view('pages.master-data.channel-ticket.index');
    }

    public function getData(Request $request)
    {
        $query = ChannelTicket::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->get('per_page', 10);
        $items = $query->latest()->paginate($perPage);

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        ChannelTicket::create($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Channel Ticket created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = ChannelTicket::findOrFail($id);
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Channel Ticket updated successfully.']);
    }

    public function destroy($id)
    {
        $record = ChannelTicket::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Channel Ticket deleted successfully.']);
    }
}