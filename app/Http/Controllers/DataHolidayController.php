<?php

namespace App\Http\Controllers;

use App\Models\DataHoliday;
use Illuminate\Http\Request;

class DataHolidayController extends Controller
{
    public function index()
    {
        return view('pages.master-data.data-holiday.index');
    }

    public function getData(Request $request)
    {
        $query = DataHoliday::query();

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
            'name'       => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'status'     => 'required|in:Aktif,Non Aktif'
        ]);
        DataHoliday::create($request->only('name', 'start_date', 'end_date', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Holiday created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $record = DataHoliday::findOrFail($id);
        $request->validate([
            'name'       => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'status'     => 'required|in:Aktif,Non Aktif'
        ]);
        $record->update($request->only('name', 'start_date', 'end_date', 'status'));
        return response()->json(['success' => true, 'message' => 'Data Holiday updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DataHoliday::findOrFail($id);
        $record->delete();
        return response()->json(['success' => true, 'message' => 'Data Holiday deleted successfully.']);
    }
}