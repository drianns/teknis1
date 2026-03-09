<?php

namespace App\Http\Controllers;

use App\Models\DataGroupName;
use Illuminate\Http\Request;

class DataGroupNameController extends Controller
{
    public function index()
    {
        return view('pages.master-data.data-group-name.index');
    }

    public function getData(Request $request)
    {
        $query = DataGroupName::query();

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
        DataGroupName::create($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Group Name created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataGroupName::findOrFail($id);
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Group Name updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataGroupName::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Group Name deleted successfully.']);
    }
}