<?php

namespace App\Http\Controllers;

use App\Models\DataAuxReason;
use Illuminate\Http\Request;

class DataAuxReasonController extends Controller
{
    public function index()
    {
        return view('pages.master-data.data-aux-reason.index');
    }

    public function getData(Request $request)
    {
        $query = DataAuxReason::query();

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
        DataAuxReason::create($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Aux Reason created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataAuxReason::findOrFail($id);
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Aux Reason updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataAuxReason::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Aux Reason deleted successfully.']);
    }
}