<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatTicketUser;

class DataTableCustomerController extends Controller
{
    public function index()
    {
        return view('pages.master-customer.data-table-customer.index');
    }

    public function getData(Request $request)
    {
        $query = ChatTicketUser::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 10);
        $data    = $query->latest()->paginate($perPage);

        return response()->json($data);
    }

    public function export(Request $request)
    {
        $customers = ChatTicketUser::all();

        $headers = ['No', 'Name', 'Email', 'Phone', 'Created At'];
        $rows    = $customers->map(fn($c, $i) => [
            $i + 1, $c->name, $c->email ?? '-', $c->phone ?? '-',
            $c->created_at?->format('d M Y') ?? '-'
        ])->toArray();

        $sep   = "\t";
        $lines = [implode($sep, $headers)];
        foreach ($rows as $row) {
            $lines[] = implode($sep, $row);
        }
        $csv = "\xEF\xBB\xBF" . implode("\r\n", $lines) . "\r\n";

        return response($csv, 200, [
            'Content-Type'        => 'text/tab-separated-values; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data_customer_' . now()->format('Ymd') . '.csv"',
        ]);
    }
}
