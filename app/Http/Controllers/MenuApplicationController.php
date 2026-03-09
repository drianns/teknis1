<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuApplication;

class MenuApplicationController extends Controller
{
    /**
     * Display listing
     */
    public function index()
    {
        return view('pages.setting-application.menu-application.index');
    }

    /**
     * Get data for AJAX table
     */
    public function getData(Request $request)
    {
        $query = MenuApplication::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('menu_name', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%")
                    ->orWhere('icon', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $menus = $query->orderBy('number', 'asc')->paginate($perPage);

        return response()->json($menus);
    }

    /**
     * Store new menu
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_name' => 'required|string|max:255',
            'number' => 'required|integer',
            'url' => 'nullable|string|max:255',
            'icon' => 'required|string|max:100',
            'type' => 'required|in:Yes,No'
        ]);

        $menu = MenuApplication::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu created successfully',
            'data' => $menu
        ]);
    }

    /**
     * Show single menu
     */
    public function show($id)
    {
        $menu = MenuApplication::findOrFail($id);
        return response()->json($menu);
    }

    /**
     * Update menu
     */
    public function update(Request $request, $id)
    {
        $menu = MenuApplication::findOrFail($id);

        $validated = $request->validate([
            'menu_name' => 'required|string|max:255',
            'number' => 'required|integer',
            'url' => 'nullable|string|max:255',
            'icon' => 'required|string|max:100',
            'type' => 'required|in:Yes,No'
        ]);

        $menu->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu updated successfully'
        ]);
    }

    /**
     * Delete menu
     */
    public function destroy($id)
    {
        $menu = MenuApplication::findOrFail($id);
        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu deleted successfully'
        ]);
    }
}
