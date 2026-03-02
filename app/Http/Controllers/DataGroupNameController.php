<?php

namespace App\Http\Controllers;

use App\Models\DataGroupName;
use Illuminate\Http\Request;

class DataGroupNameController extends Controller
{
    public function index()
    {
        $items = DataGroupName::latest()->paginate(15);
        return view('pages.master-data.data-group-name.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DataGroupName::create($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Group Name created successfully.');
    }

    public function update(Request $request, DataGroupName $record)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Group Name updated successfully.');
    }

    public function destroy(DataGroupName $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Group Name deleted successfully.');
    }
}