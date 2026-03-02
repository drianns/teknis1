<?php

namespace App\Http\Controllers;

use App\Models\DataSubCategory;
use Illuminate\Http\Request;

class DataSubCategoryController extends Controller
{
    public function index()
    {
        $items = DataSubCategory::with(['brand', 'dataType', 'category', 'meta', 'escalationUnit'])->latest()->paginate(15);
        $brands = \App\Models\DataBrandName::all();
        $types = \App\Models\DataType::all();
        $categories = \App\Models\DataCategory::all();
        $metas = \App\Models\DataMeta::all();
        $units = \App\Models\DepartmentEscalationUnit::all();
        return view('pages.master-data.data-sub-category.index', compact('items', 'brands', 'types', 'categories', 'metas', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_brand_name_id' => 'required|exists:data_brand_names,id',
            'data_type_id' => 'required|exists:data_types,id',
            'data_category_id' => 'required|exists:data_categories,id',
            'data_meta_id' => 'required|exists:data_metas,id',
            'name' => 'required|string|max:255',
            'department_escalation_unit_id' => 'nullable|exists:department_escalation_units,id',
            'escalation_layer' => 'nullable|integer',
            'sla' => 'nullable|integer',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        
        $data = $request->all();
        $data['created_by'] = auth()->user()->name ?? 'Admin';
        
        DataSubCategory::create($data);
        return redirect()->back()->with('success', 'Data Sub Category created successfully.');
    }

    public function update(Request $request, DataSubCategory $record)
    {
        $request->validate([
            'data_brand_name_id' => 'required|exists:data_brand_names,id',
            'data_type_id' => 'required|exists:data_types,id',
            'data_category_id' => 'required|exists:data_categories,id',
            'data_meta_id' => 'required|exists:data_metas,id',
            'name' => 'required|string|max:255',
            'department_escalation_unit_id' => 'nullable|exists:department_escalation_units,id',
            'escalation_layer' => 'nullable|integer',
            'sla' => 'nullable|integer',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        
        $record->update($request->all());
        return redirect()->back()->with('success', 'Data Sub Category updated successfully.');
    }

    public function destroy(DataSubCategory $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Sub Category deleted successfully.');
    }
}