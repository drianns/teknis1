<?php

namespace App\Http\Controllers;

use App\Models\DataMeta;
use App\Models\DataBrandName;
use App\Models\DataType;
use App\Models\DataCategory;
use Illuminate\Http\Request;

class DataMetaController extends Controller
{
    public function index()
    {
        $brands     = DataBrandName::orderBy('name')->get();
        $types      = DataType::orderBy('name')->get();
        $categories = DataCategory::orderBy('name')->get();
        return view('pages.master-data.data-meta.index', compact('brands', 'types', 'categories'));
    }

    public function getData(Request $request)
    {
        $query = DataMeta::with(['brand', 'dataType', 'category']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('brand', fn($b) => $b->where('name', 'like', '%' . $search . '%'))
                  ->orWhereHas('dataType', fn($t) => $t->where('name', 'like', '%' . $search . '%'))
                  ->orWhereHas('category', fn($c) => $c->where('name', 'like', '%' . $search . '%'));
            });
        }

        $perPage = $request->get('per_page', 10);
        $items = $query->latest()->paginate($perPage);

        $items->getCollection()->transform(function ($item) {
            $item->brand_name    = $item->brand->name ?? '-';
            $item->type_name     = $item->dataType->name ?? '-';
            $item->category_name = $item->category->name ?? '-';
            return $item;
        });

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_brand_name_id' => 'required|exists:data_brand_names,id',
            'data_type_id'       => 'required|exists:data_types,id',
            'data_category_id'   => 'required|exists:data_categories,id',
            'name'               => 'required|string|max:255',
            'status'             => 'required|in:Aktif,Non Aktif'
        ]);
        $data = $request->only('data_brand_name_id', 'data_type_id', 'data_category_id', 'name', 'status');
        $data['created_by'] = auth()->user()->name ?? 'Admin';
        DataMeta::create($data);
        return response()->json(['success' => true, 'message' => 'Data Meta created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataMeta::findOrFail($id);
        $request->validate([
            'data_brand_name_id' => 'required|exists:data_brand_names,id',
            'data_type_id'       => 'required|exists:data_types,id',
            'data_category_id'   => 'required|exists:data_categories,id',
            'name'               => 'required|string|max:255',
            'status'             => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('data_brand_name_id', 'data_type_id', 'data_category_id', 'name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Meta updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataMeta::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Meta deleted successfully.']);
    }
}