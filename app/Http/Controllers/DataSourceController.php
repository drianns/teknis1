<?php

namespace App\Http\Controllers;

use App\Models\DataSource;
use Illuminate\Http\Request;

class DataSourceController extends Controller
{
    public function index()
    {
        return view('pages.master-data.data-source.index');
    }

    public function getData(Request $request)
    {
        $query = DataSource::query();

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
        DataSource::create($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Source created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataSource::findOrFail($id);
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Source updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataSource::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Source deleted successfully.']);
    }
}