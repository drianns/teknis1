<?php

namespace App\Http\Controllers;

use App\Models\DataStatusTicket;
use Illuminate\Http\Request;

class DataStatusTicketController extends Controller
{
    public function index()
    {
        $items = DataStatusTicket::latest()->paginate(15);
        return view('pages.master-data.data-status-ticket.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DataStatusTicket::create($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Status Ticket created successfully.');
    }

    public function update(Request $request, DataStatusTicket $record)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Status Ticket updated successfully.');
    }

    public function destroy(DataStatusTicket $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Status Ticket deleted successfully.');
    }
}