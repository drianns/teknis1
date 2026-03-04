<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataTableCustomerController extends Controller
{
    public function index()
    {
        $customers = collect([]);

        return view('pages.master-customer.data-table-customer.index', compact('customers'));
    }

    public function export()
    {
        // This will be implemented later with actual Excel export logic
        return response()->json(['message' => 'Export functionality will be implemented']);
    }
}
