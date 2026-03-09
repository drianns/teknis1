<?php

namespace App\Http\Controllers;

use App\Models\DataMaxHandle;
use Illuminate\Http\Request;

class DataMaxHandleController extends Controller
{
    public function index()
    {
        return view('pages.master-data.data-max-handle.index');
    }

    public function getData(Request $request)
    {
        $query = DataMaxHandle::query();

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
        DataMaxHandle::create($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Max Handle created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataMaxHandle::findOrFail($id);
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Max Handle updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataMaxHandle::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Max Handle deleted successfully.']);
    }
}