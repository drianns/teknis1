<?php

namespace App\Http\Controllers;

use App\Models\DataFulfillment;
use Illuminate\Http\Request;

class DataFulfillmentController extends Controller
{
    public function index()
    {
        return view('pages.master-data.data-fulfillment.index');
    }

    public function getData(Request $request)
    {
        $query = DataFulfillment::query();

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
        DataFulfillment::create($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Fulfillment created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataFulfillment::findOrFail($id);
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Fulfillment updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataFulfillment::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Fulfillment deleted successfully.']);
    }
}