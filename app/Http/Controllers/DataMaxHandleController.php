<?php

namespace App\Http\Controllers;

use App\Models\DataMaxHandle;
use Illuminate\Http\Request;

class DataMaxHandleController extends Controller
{
    public function index()
    {
        $items = DataMaxHandle::latest()->paginate(15);
        return view('pages.master-data.data-max-handle.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DataMaxHandle::create($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Max Handle created successfully.');
    }

    public function update(Request $request, DataMaxHandle $record)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Max Handle updated successfully.');
    }

    public function destroy(DataMaxHandle $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Max Handle deleted successfully.');
    }
}