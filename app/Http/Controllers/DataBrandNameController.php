<?php

namespace App\Http\Controllers;

use App\Models\DataBrandName;
use App\Models\DataBrandCategory;
use Illuminate\Http\Request;

class DataBrandNameController extends Controller
{
    public function index()
    {
        $categories = DataBrandCategory::orderBy('name')->get();
        return view('pages.master-data.data-brand-name.index', compact('categories'));
    }

    public function getData(Request $request)
    {
        $query = DataBrandName::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('category', fn($c) => $c->where('name', 'like', '%' . $search . '%'));
            });
        }

        $perPage = $request->get('per_page', 10);
        $items = $query->latest()->paginate($perPage);

        $items->getCollection()->transform(function ($item) {
            $item->category_name = $item->category->name ?? '-';
            return $item;
        });

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_brand_category_id' => 'required|exists:data_brand_categories,id',
            'name'                   => 'required|string|max:255',
            'status'                 => 'required|in:Aktif,Non Aktif'
        ]);
        DataBrandName::create($request->only('data_brand_category_id', 'name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Brand Name created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataBrandName::findOrFail($id);
        $request->validate([
            'data_brand_category_id' => 'required|exists:data_brand_categories,id',
            'name'                   => 'required|string|max:255',
            'status'                 => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('data_brand_category_id', 'name', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Brand Name updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataBrandName::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Brand Name deleted successfully.']);
    }
}