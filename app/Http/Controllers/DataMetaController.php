<?php

namespace App\Http\Controllers;

use App\Models\DataMeta;
use Illuminate\Http\Request;

class DataMetaController extends Controller
{
    public function index()
    {
        $items = DataMeta::with(['brand', 'dataType', 'category'])->latest()->paginate(15);
        $brands = \App\Models\DataBrandName::all();
        $types = \App\Models\DataType::all();
        $categories = \App\Models\DataCategory::all();
        return view('pages.master-data.data-meta.index', compact('items', 'brands', 'types', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_brand_name_id' => 'required|exists:data_brand_names,id',
            'data_type_id' => 'required|exists:data_types,id',
            'data_category_id' => 'required|exists:data_categories,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        
        $data = $request->only('data_brand_name_id', 'data_type_id', 'data_category_id', 'name', 'status');
        $data['created_by'] = auth()->user()->name ?? 'Admin';
        
        DataMeta::create($data);
        return redirect()->back()->with('success', 'Data Meta created successfully.');
    }

    public function update(Request $request, DataMeta $record)
    {
        $request->validate([
            'data_brand_name_id' => 'required|exists:data_brand_names,id',
            'data_type_id' => 'required|exists:data_types,id',
            'data_category_id' => 'required|exists:data_categories,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        
        $record->update($request->only('data_brand_name_id', 'data_type_id', 'data_category_id', 'name', 'status'));
        return redirect()->back()->with('success', 'Data Meta updated successfully.');
    }

    public function destroy(DataMeta $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Meta deleted successfully.');
    }
}