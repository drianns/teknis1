<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubMenuApplication;

class SubMenuApplicationController extends Controller
{
    /**
     * Display listing
     */
    public function index(Request $request)
    {
        $query = SubMenuApplication::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('menu_name', 'like', "%{$search}%")
                    ->orWhere('sub_menu_name', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $subMenus = $query->orderBy('id', 'desc')->paginate($perPage);

        return view('pages.setting-application.sub-menu-application.index', compact('subMenus'));
    }

    /**
     * Store new sub menu
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_name' => 'required|string|max:255',
            'sub_menu_name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'type' => 'required|in:Yes,No'
        ]);

        $subMenu = SubMenuApplication::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sub Menu created successfully',
            'data' => $subMenu
        ]);
    }

    /**
     * Show single sub menu
     */
    public function show($id)
    {
        $subMenu = SubMenuApplication::findOrFail($id);
        return response()->json($subMenu);
    }

    /**
     * Update sub menu
     */
    public function update(Request $request, $id)
    {
        $subMenu = SubMenuApplication::findOrFail($id);

        $validated = $request->validate([
            'menu_name' => 'required|string|max:255',
            'sub_menu_name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'type' => 'required|in:Yes,No'
        ]);

        $subMenu->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sub Menu updated successfully'
        ]);
    }

    /**
     * Delete sub menu
     */
    public function destroy($id)
    {
        $subMenu = SubMenuApplication::findOrFail($id);
        $subMenu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sub Menu deleted successfully'
        ]);
    }
}
