<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SettingAgentCallController extends Controller
{
    public function index()
    {
        return view('pages.setup-channel-call.setting-agent-call.index');
    }

    public function getData(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%');
            });
        }

        $perPage = $request->input('per_page', 10);
        $data = $query->latest()->paginate($perPage);

        return response()->json($data);
    }
}
