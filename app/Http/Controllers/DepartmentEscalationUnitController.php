<?php

namespace App\Http\Controllers;

use App\Models\DepartmentEscalationUnit;
use Illuminate\Http\Request;

class DepartmentEscalationUnitController extends Controller
{
    public function index()
    {
        $items = DepartmentEscalationUnit::latest()->paginate(15);
        return view('pages.master-data.department-escalation-unit.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DepartmentEscalationUnit::create($request->only('name', 'email', 'status'));
        return redirect()->back()->with('success', 'Department Escalation Unit created successfully.');
    }

    public function update(Request $request, DepartmentEscalationUnit $record)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'email', 'status'));
        return redirect()->back()->with('success', 'Department Escalation Unit updated successfully.');
    }

    public function destroy(DepartmentEscalationUnit $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Department Escalation Unit deleted successfully.');
    }
}