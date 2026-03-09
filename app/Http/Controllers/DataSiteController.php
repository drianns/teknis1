<?php

namespace App\Http\Controllers;

use App\Models\DataSite;
use Illuminate\Http\Request;

class DataSiteController extends Controller
{
    public function index()
    {
        return view('pages.master-data.data-site.index');
    }

    public function getData(Request $request)
    {
        $query = DataSite::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        $perPage = $request->get('per_page', 10);
        $items = $query->latest()->paginate($perPage);

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status'   => 'required|in:Aktif,Non Aktif'
        ]);
        DataSite::create($request->only('name', 'location', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Site created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataSite::findOrFail($id);
        $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status'   => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'location', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Site updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataSite::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Site deleted successfully.']);
    }
}