<?php

namespace App\Http\Controllers;

use App\Models\DataGroupAgent;
use Illuminate\Http\Request;

class DataGroupAgentController extends Controller
{
    public function index()
    {
        return view('pages.master-data.data-group-agent.index');
    }

    public function getData(Request $request)
    {
        $query = DataGroupAgent::query();

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
        DataGroupAgent::create($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Group Agent created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataGroupAgent::findOrFail($id);
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Group Agent updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataGroupAgent::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Group Agent deleted successfully.']);
    }
}