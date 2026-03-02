<?php

namespace App\Http\Controllers;

use App\Models\DataType;
use Illuminate\Http\Request;

class DataTypeController extends Controller
{
    public function index()
    {
        $items = DataType::with('brand')->latest()->paginate(15);
        $brands = \App\Models\DataBrandName::all();
        return view('pages.master-data.data-type.index', compact('items', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_brand_name_id' => 'nullable|exists:data_brand_names,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DataType::create($request->only('data_brand_name_id', 'name', 'status'));
        return redirect()->back()->with('success', 'Data Type created successfully.');
    }

    public function update(Request $request, DataType $record)
    {
        $request->validate([
            'data_brand_name_id' => 'nullable|exists:data_brand_names,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('data_brand_name_id', 'name', 'status'));
        return redirect()->back()->with('success', 'Data Type updated successfully.');
    }

    public function destroy(DataType $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Type deleted successfully.');
    }
}