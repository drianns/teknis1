<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SettingAgentEmailController extends Controller
{
    public function index()
    {
        return view('pages.setup-channel-email.setting-agent-email.index');
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

        $perPage = $request->input('per_page', 20);
        return response()->json($query->latest()->paginate($perPage));
    }
}
