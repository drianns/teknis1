<?php

namespace App\Http\Controllers;

use App\Models\DataStatusTicket;
use Illuminate\Http\Request;

class DataStatusTicketController extends Controller
{
    public function index()
    {
        return view('pages.master-data.data-status-ticket.index');
    }

    public function getData(Request $request)
    {
        $query = DataStatusTicket::query();

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
        DataStatusTicket::create($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Status Ticket created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataStatusTicket::findOrFail($id);
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Status Ticket updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataStatusTicket::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Status Ticket deleted successfully.']);
    }
}