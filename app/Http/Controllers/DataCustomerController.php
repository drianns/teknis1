<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataCustomerController extends Controller
{
    public function index()
    {
        $customers = collect([]);

        return view('pages.master-customer.data-customer.index', compact('customers'));
    }
}
