<?php

namespace App\Http\Controllers;

use App\Models\DataActivity;
use Illuminate\Http\Request;

class DataActivityController extends Controller
{
    public function index()
    {
        $items = DataActivity::latest()->paginate(15);
        return view('pages.master-data.data-activity.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DataActivity::create($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Activity created successfully.');
    }

    public function update(Request $request, DataActivity $record)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Activity updated successfully.');
    }

    public function destroy(DataActivity $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Activity deleted successfully.');
    }
}