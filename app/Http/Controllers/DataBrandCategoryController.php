<?php

namespace App\Http\Controllers;

use App\Models\DataBrandCategory;
use Illuminate\Http\Request;

class DataBrandCategoryController extends Controller
{
    public function index()
    {
        $items = DataBrandCategory::with('group')->latest()->paginate(15);
        $groups = \App\Models\DataGroupName::all();
        return view('pages.master-data.data-brand-category.index', compact('items', 'groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_group_name_id' => 'required|exists:data_group_names,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DataBrandCategory::create($request->only('data_group_name_id', 'name', 'status'));
        return redirect()->back()->with('success', 'Data Brand Category created successfully.');
    }

    public function update(Request $request, DataBrandCategory $record)
    {
        $request->validate([
            'data_group_name_id' => 'required|exists:data_group_names,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('data_group_name_id', 'name', 'status'));
        return redirect()->back()->with('success', 'Data Brand Category updated successfully.');
    }

    public function destroy(DataBrandCategory $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Brand Category deleted successfully.');
    }
}