<?php

namespace App\Http\Controllers;

use App\Models\DataCategory;
use Illuminate\Http\Request;

class DataCategoryController extends Controller
{
    public function index()
    {
        $items = DataCategory::with(['brand', 'dataType'])->latest()->paginate(15);
        $brands = \App\Models\DataBrandName::all();
        $types = \App\Models\DataType::all();
        return view('pages.master-data.data-category.index', compact('items', 'brands', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_brand_name_id' => 'required|exists:data_brand_names,id',
            'data_type_id' => 'required|exists:data_types,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DataCategory::create($request->only('data_brand_name_id', 'data_type_id', 'name', 'status'));
        return redirect()->back()->with('success', 'Data Category created successfully.');
    }

    public function update(Request $request, DataCategory $record)
    {
        $request->validate([
            'data_brand_name_id' => 'required|exists:data_brand_names,id',
            'data_type_id' => 'required|exists:data_types,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('data_brand_name_id', 'data_type_id', 'name', 'status'));
        return redirect()->back()->with('success', 'Data Category updated successfully.');
    }

    public function destroy(DataCategory $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Category deleted successfully.');
    }
}