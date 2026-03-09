<?php

namespace App\Http\Controllers;

use App\Models\DataFulfillmentLocation;
use Illuminate\Http\Request;

class DataFulfillmentLocationController extends Controller
{
    public function index()
    {
        return view('pages.master-data.data-fulfillment-location.index');
    }

    public function getData(Request $request)
    {
        $query = DataFulfillmentLocation::query();

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
        DataFulfillmentLocation::create($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Fulfillment Location created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataFulfillmentLocation::findOrFail($id);
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Fulfillment Location updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataFulfillmentLocation::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Fulfillment Location deleted successfully.']);
    }
}