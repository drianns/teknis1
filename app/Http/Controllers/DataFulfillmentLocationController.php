<?php

namespace App\Http\Controllers;

use App\Models\DataFulfillmentLocation;
use Illuminate\Http\Request;

class DataFulfillmentLocationController extends Controller
{
    public function index()
    {
        $items = DataFulfillmentLocation::latest()->paginate(15);
        return view('pages.master-data.data-fulfillment-location.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DataFulfillmentLocation::create($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Fulfillment Location created successfully.');
    }

    public function update(Request $request, DataFulfillmentLocation $record)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Fulfillment Location updated successfully.');
    }

    public function destroy(DataFulfillmentLocation $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Fulfillment Location deleted successfully.');
    }
}