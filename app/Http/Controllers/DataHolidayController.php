<?php

namespace App\Http\Controllers;

use App\Models\DataHoliday;
use Illuminate\Http\Request;

class DataHolidayController extends Controller
{
    public function index()
    {
        $items = DataHoliday::latest()->paginate(15);
        return view('pages.master-data.data-holiday.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        DataHoliday::create($request->only('name', 'start_date', 'end_date', 'status'));
        return redirect()->back()->with('success', 'Data Holidays created successfully.');
    }

    public function update(Request $request, DataHoliday $record)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'start_date', 'end_date', 'status'));
        return redirect()->back()->with('success', 'Data Holidays updated successfully.');
    }

    public function destroy(DataHoliday $record)
    {
        $record->delete();
        return redirect()->back()->with('success', 'Data Holidays deleted successfully.');
    }
}