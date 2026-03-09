<?php

namespace App\Http\Controllers;

use App\Models\DepartmentEscalationUnit;
use Illuminate\Http\Request;

class DepartmentEscalationUnitController extends Controller
{
    public function index()
    {
        return view('pages.master-data.department-escalation-unit.index');
    }

    public function getData(Request $request)
    {
        $query = DepartmentEscalationUnit::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->get('per_page', 10);
        $items = $query->latest()->paginate($perPage);

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DepartmentEscalationUnit::create($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Department Escalation Unit created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DepartmentEscalationUnit::findOrFail($id);
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'status'));
        return response()->json(['success' => true, 'message' => 'Department Escalation Unit updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DepartmentEscalationUnit::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Department Escalation Unit deleted successfully.']);
    }
}