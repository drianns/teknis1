<?php

namespace App\Http\Controllers;

use App\Models\DataFulfillment;
use Illuminate\Http\Request;

class DataFulfillmentController extends Controller
{
    public function index()
    {
        $items = DataFulfillment::latest()->paginate(15);
        return view('pages.master-data.data-fulfillment.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DataFulfillment::create($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Fulfillment created successfully.');
    }

    public function update(Request $request, DataFulfillment $record)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Fulfillment updated successfully.');
    }

    public function destroy(DataFulfillment $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Fulfillment deleted successfully.');
    }
}