<?php

namespace App\Http\Controllers;

use App\Models\DataBrandCategory;
use App\Models\DataGroupName;
use Illuminate\Http\Request;

class DataBrandCategoryController extends Controller
{
    public function index()
    {
        $groups = DataGroupName::orderBy('name')->get();
        return view('pages.master-data.data-brand-category.index', compact('groups'));
    }

    public function getData(Request $request)
    {
        $query = DataBrandCategory::with('group');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('group', fn($g) => $g->where('name', 'like', '%' . $search . '%'));
            });
        }

        $perPage = $request->get('per_page', 10);
        $items = $query->latest()->paginate($perPage);

        $items->getCollection()->transform(function ($item) {
            $item->group_name = $item->group->name ?? '-';
            return $item;
        });

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_group_name_id' => 'required|exists:data_group_names,id',
            'name'               => 'required|string|max:255',
            'status'             => 'required|in:Aktif,Non Aktif'
        ]);
        DataBrandCategory::create($request->only('data_group_name_id', 'name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Brand Category created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataBrandCategory::findOrFail($id);
        $request->validate([
            'data_group_name_id' => 'required|exists:data_group_names,id',
            'name'               => 'required|string|max:255',
            'status'             => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('data_group_name_id', 'name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Brand Category updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataBrandCategory::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Brand Category deleted successfully.']);
    }
}