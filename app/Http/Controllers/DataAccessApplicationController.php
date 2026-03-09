<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DataAccessApplication;

class DataAccessApplicationController extends Controller
{
    public function index()
    {
        return view('pages.management-user.data-access-application.index');
    }

    public function getData(Request $request)
    {
        $query = DataAccessApplication::query();

        if ($request->has('level_user') && $request->level_user != '') {
            $query->where('level_user', $request->level_user);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('menu_level1', 'like', '%' . $request->search . '%')
                  ->orWhere('menu_level2', 'like', '%' . $request->search . '%')
                  ->orWhere('menu_level3', 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->paginate($request->get('limit', 10));

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'agentRole' => 'required',
            'menuLevel1' => 'required',
        ]);

        $access = DataAccessApplication::create([
            'level_user' => $request->agentRole,
            'menu_level1' => $request->menuLevel1,
            'menu_level2' => $request->menuLevel2,
            'menu_level3' => $request->menuLevel3,
            'description' => $request->description,
            'created_by' => auth()->user()->userName ?? 'Admin',
        ]);

        return response()->json(['message' => 'Access granted successfully', 'access' => $access]);
    }

    public function destroy($id)
    {
        $access = DataAccessApplication::findOrFail($id);
        $access->delete();

        return response()->json(['message' => 'Access revoked successfully']);
    }
}
