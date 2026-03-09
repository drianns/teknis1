<?php

namespace App\Http\Controllers;

use App\Models\DataType;
use App\Models\DataBrandName;
use Illuminate\Http\Request;

class DataTypeController extends Controller
{
    public function index()
    {
        $brands = DataBrandName::orderBy('name')->get();
        return view('pages.master-data.data-type.index', compact('brands'));
    }

    public function getData(Request $request)
    {
        $query = DataType::with('brand');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('brand', fn($b) => $b->where('name', 'like', '%' . $search . '%'));
            });
        }

        $perPage = $request->get('per_page', 10);
        $items = $query->latest()->paginate($perPage);

        $items->getCollection()->transform(function ($item) {
            $item->brand_name = $item->brand->name ?? '-';
            return $item;
        });

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_brand_name_id' => 'nullable|exists:data_brand_names,id',
            'name'               => 'required|string|max:255',
            'status'             => 'required|in:Aktif,Non Aktif'
        ]);
        DataType::create($request->only('data_brand_name_id', 'name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Type created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataType::findOrFail($id);
        $request->validate([
            'data_brand_name_id' => 'nullable|exists:data_brand_names,id',
            'name'               => 'required|string|max:255',
            'status'             => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('data_brand_name_id', 'name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Type updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataType::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Type deleted successfully.']);
    }
}