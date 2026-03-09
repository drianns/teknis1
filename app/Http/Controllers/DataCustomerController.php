<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatTicketUser;

class DataCustomerController extends Controller
{
    public function index()
    {
        return view('pages.master-customer.data-customer.index', ['customers' => collect([])]);
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
}
