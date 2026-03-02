<?php

namespace App\Http\Controllers;

use App\Models\DataAuxReason;
use Illuminate\Http\Request;

class DataAuxReasonController extends Controller
{
    public function index()
    {
        $items = DataAuxReason::latest()->paginate(15);
        return view('pages.master-data.data-aux-reason.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DataAuxReason::create($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Aux Reason created successfully.');
    }

    public function update(Request $request, DataAuxReason $record)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Aux Reason updated successfully.');
    }

    public function destroy(DataAuxReason $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Aux Reason deleted successfully.');
    }
}