<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class MonitoringLoginController extends Controller
{
    public function index(Request $request)
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

        $entries = $request->get('entries', 10);
        $users = $query->paginate($entries)->withQueryString();

        // Dummy stats representing the 4 cards at the top
        $cardStats = [
            'total_user' => User::count(),
            'not_login' => 40,
            'login' => 1,
            'aux' => 0,
        ];

        return view('pages.data-login.monitoring-login.index', compact('users', 'cardStats'));
    }
}
