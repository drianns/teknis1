<?php

namespace App\Http\Controllers;

use App\Models\DataCategory;
use App\Models\DataBrandName;
use App\Models\DataType;
use Illuminate\Http\Request;

class DataCategoryController extends Controller
{
    public function index()
    {
        $brands = DataBrandName::orderBy('name')->get();
        $types  = DataType::with('brand')->orderBy('name')->get();
        return view('pages.master-data.data-category.index', compact('brands', 'types'));
    }

    public function getData(Request $request)
    {
        $query = DataCategory::with(['brand', 'dataType']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('brand', fn($b) => $b->where('name', 'like', '%' . $search . '%'))
                  ->orWhereHas('dataType', fn($t) => $t->where('name', 'like', '%' . $search . '%'));
            });
        }

        $perPage = $request->get('per_page', 10);
        $items = $query->latest()->paginate($perPage);

        $items->getCollection()->transform(function ($item) {
            $item->brand_name = $item->brand->name ?? '-';
            $item->type_name  = $item->dataType->name ?? '-';
            return $item;
        });

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_brand_name_id' => 'required|exists:data_brand_names,id',
            'data_type_id'       => 'required|exists:data_types,id',
            'name'               => 'required|string|max:255',
            'status'             => 'required|in:Aktif,Non Aktif'
        ]);
        DataCategory::create($request->only('data_brand_name_id', 'data_type_id', 'name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Category created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataCategory::findOrFail($id);
        $request->validate([
            'data_brand_name_id' => 'required|exists:data_brand_names,id',
            'data_type_id'       => 'required|exists:data_types,id',
            'name'               => 'required|string|max:255',
            'status'             => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('data_brand_name_id', 'data_type_id', 'name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Category updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataCategory::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Category deleted successfully.']);
    }
}