<?php

namespace App\Http\Controllers;

use App\Models\DataGroupAgent;
use Illuminate\Http\Request;

class DataGroupAgentController extends Controller
{
    public function index()
    {
        $items = DataGroupAgent::latest()->paginate(15);
        return view('pages.master-data.data-group-agent.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DataGroupAgent::create($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Group Agent created successfully.');
    }

    public function update(Request $request, DataGroupAgent $record)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return redirect()->back()->with('success', 'Data Group Agent updated successfully.');
    }

    public function destroy(DataGroupAgent $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Group Agent deleted successfully.');
    }
}