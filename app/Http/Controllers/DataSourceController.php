<?php

namespace App\Http\Controllers;

use App\Models\DataSource;
use Illuminate\Http\Request;

class DataSourceController extends Controller
{
    public function index()
    {
        $items = DataSource::latest()->paginate(15);
        return view('pages.master-data.data-source.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DataSource::create($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Source created successfully.');
    }

    public function update(Request $request, DataSource $record)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Source updated successfully.');
    }

    public function destroy(DataSource $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Source deleted successfully.');
    }
}