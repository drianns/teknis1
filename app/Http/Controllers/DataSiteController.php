<?php

namespace App\Http\Controllers;

use App\Models\DataSite;
use Illuminate\Http\Request;

class DataSiteController extends Controller
{
    public function index()
    {
        $items = DataSite::latest()->paginate(15);
        return view('pages.master-data.data-site.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DataSite::create($request->only('name', 'location', 'status'));
        return redirect()->back()->with('success', 'Data Site created successfully.');
    }

    public function update(Request $request, DataSite $record)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'location', 'status'));
        return redirect()->back()->with('success', 'Data Site updated successfully.');
    }

    public function destroy(DataSite $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Site deleted successfully.');
    }
}