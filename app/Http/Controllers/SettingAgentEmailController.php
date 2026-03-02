<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SettingAgentEmailController extends Controller
{
    public function index(Request $request)
    {
        $users = User::latest()->take(50)->get();

        // For the modal table
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
        $modalUsers = $query->paginate($entries)->withQueryString();

        return view('pages.setting-agent-email.index', compact('users', 'modalUsers'));
    }
}
