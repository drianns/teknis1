<?php

namespace App\Http\Controllers;

use App\Models\DetailMenuApplication;
use Illuminate\Http\Request;

class DetailMenuApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.setting-application.detail-menu-application.index');
    }

    /**
     * Get data for AJAX table
     */
    public function getData(Request $request)
    {
        $query = DetailMenuApplication::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('menu_name', 'like', "%{$search}%")
                    ->orWhere('sub_menu_name', 'like', "%{$search}%")
                    ->orWhere('detail_menu_name', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $detailMenus = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json($detailMenus);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_name' => 'required|string|max:255',
            'sub_menu_name' => 'nullable|string|max:255',
            'detail_menu_name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'type' => 'required|in:Yes,No'
        ]);

        $detailMenu = DetailMenuApplication::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Detail Menu created successfully',
            'data' => $detailMenu
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $detailMenu = DetailMenuApplication::findOrFail($id);
        return response()->json($detailMenu);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $detailMenu = DetailMenuApplication::findOrFail($id);

        $validated = $request->validate([
            'menu_name' => 'required|string|max:255',
            'sub_menu_name' => 'nullable|string|max:255',
            'detail_menu_name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'type' => 'required|in:Yes,No'
        ]);

        $detailMenu->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Detail Menu updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $detailMenu = DetailMenuApplication::findOrFail($id);
        $detailMenu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Detail Menu deleted successfully'
        ]);
    }
}
