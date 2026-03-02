<?php

namespace App\Http\Controllers;

use App\Models\DataBrandName;
use Illuminate\Http\Request;

class DataBrandNameController extends Controller
{
    public function index()
    {
        $items = DataBrandName::with('category')->latest()->paginate(15);
        $categories = \App\Models\DataBrandCategory::all();
        return view('pages.master-data.data-brand-name.index', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_brand_category_id' => 'required|exists:data_brand_categories,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DataBrandName::create($request->only('data_brand_category_id', 'name', 'status'));
        return redirect()->back()->with('success', 'Data Brand Name created successfully.');
    }

    public function update(Request $request, DataBrandName $record)
    {
        $request->validate([
            'data_brand_category_id' => 'required|exists:data_brand_categories,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('data_brand_category_id', 'name', 'status'));
        return redirect()->back()->with('success', 'Data Brand Name updated successfully.');
    }

    public function destroy(DataBrandName $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Brand Name deleted successfully.');
    }
}