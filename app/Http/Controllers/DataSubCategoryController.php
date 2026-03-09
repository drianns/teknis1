<?php

namespace App\Http\Controllers;

use App\Models\DataSubCategory;
use App\Models\DataBrandName;
use App\Models\DataType;
use App\Models\DataCategory;
use App\Models\DataMeta;
use App\Models\DepartmentEscalationUnit;
use Illuminate\Http\Request;

class DataSubCategoryController extends Controller
{
    public function index()
    {
        $brands     = DataBrandName::orderBy('name')->get();
        $types      = DataType::orderBy('name')->get();
        $categories = DataCategory::orderBy('name')->get();
        $metas      = DataMeta::orderBy('name')->get();
        $units      = DepartmentEscalationUnit::orderBy('name')->get();
        return view('pages.master-data.data-sub-category.index', compact('brands', 'types', 'categories', 'metas', 'units'));
    }

    public function getData(Request $request)
    {
        $query = DataSubCategory::with(['brand', 'dataType', 'category', 'meta', 'escalationUnit']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('brand', fn($b) => $b->where('name', 'like', '%' . $search . '%'))
                  ->orWhereHas('category', fn($c) => $c->where('name', 'like', '%' . $search . '%'));
            });
        }

        $perPage = $request->get('per_page', 10);
        $items = $query->latest()->paginate($perPage);

        $items->getCollection()->transform(function ($item) {
            $item->brand_name           = $item->brand->name ?? '-';
            $item->type_name            = $item->dataType->name ?? '-';
            $item->category_name        = $item->category->name ?? '-';
            $item->meta_name            = $item->meta->name ?? '-';
            $item->escalation_unit_name = $item->escalationUnit->name ?? '-';
            return $item;
        });

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_brand_name_id'          => 'required|exists:data_brand_names,id',
            'data_type_id'                => 'required|exists:data_types,id',
            'data_category_id'            => 'required|exists:data_categories,id',
            'data_meta_id'                => 'required|exists:data_metas,id',
            'name'                        => 'required|string|max:255',
            'department_escalation_unit_id' => 'nullable|exists:department_escalation_units,id',
            'escalation_layer'            => 'nullable|integer',
            'sla'                         => 'nullable|integer',
            'status'                      => 'required|in:Aktif,Non Aktif'
        ]);
        $data = $request->all();
        $data['created_by'] = auth()->user()->name ?? 'Admin';
        DataSubCategory::create($data);
        return response()->json(['success' => true, 'message' => 'Data Sub Category created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataSubCategory::findOrFail($id);
        $request->validate([
            'data_brand_name_id'          => 'required|exists:data_brand_names,id',
            'data_type_id'                => 'required|exists:data_types,id',
            'data_category_id'            => 'required|exists:data_categories,id',
            'data_meta_id'                => 'required|exists:data_metas,id',
            'name'                        => 'required|string|max:255',
            'department_escalation_unit_id' => 'nullable|exists:department_escalation_units,id',
            'escalation_layer'            => 'nullable|integer',
            'sla'                         => 'nullable|integer',
            'status'                      => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->all());
        return response()->json(['success' => true, 'message' => 'Data Sub Category updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataSubCategory::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Sub Category deleted successfully.']);
    }
}