<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class MonitoringLoginController extends Controller
{
    public function index()
    {
        // Stats for the 4 cards at the top
        $cardStats = [
            'total_user' => User::count(),
            'not_login'  => 0,
            'login'      => 0,
            'aux'        => 0,
        ];

        return view('pages.data-login.monitoring-login.index', compact('cardStats'));
    }

    public function getData(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $perPage = $request->get('per_page', 10);
        $users = $query->latest()->paginate($perPage);

        return response()->json($users);
    }
}
