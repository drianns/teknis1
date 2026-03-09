<?php

namespace App\Http\Controllers;

use App\Models\DataActivity;
use Illuminate\Http\Request;

class DataActivityController extends Controller
{
    public function index()
    {
        return view('pages.master-data.data-activity.index');
    }

    public function getData(Request $request)
    {
        $query = DataActivity::query();

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
        DataActivity::create($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Activity created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataActivity::findOrFail($id);
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Activity updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataActivity::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Activity deleted successfully.']);
    }
}