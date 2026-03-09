<?php

namespace App\Http\Controllers;

use App\Models\SettingChannelAgent;
use App\Models\User;
use Illuminate\Http\Request;

class SettingChannelAgentController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('pages.setting-application.setting-channel-agent.index', compact('users'));
    }

    public function getData(Request $request)
    {
        $query = SettingChannelAgent::with('user');

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function ($sq) use ($search) {
                    $sq->where('name', 'like', '%' . $search . '%');
                })->orWhere('menu', 'like', '%' . $search . '%')
                  ->orWhere('sub_menu', 'like', '%' . $search . '%')
                  ->orWhere('detail_menu', 'like', '%' . $search . '%')
                  ->orWhere('url', 'like', '%' . $search . '%');
            });
        }

        $perPage = $request->input('per_page', 10);
        $data = $query->paginate($perPage);

        return response()->json($data);
    }

    public function show($id)
    {
        $settingChannelAgent = SettingChannelAgent::findOrFail($id);
        return response()->json($settingChannelAgent);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'menu' => 'required',
            'sub_menu' => 'required',
            'detail_menu' => 'required',
            'url' => 'required',
            'status' => 'required|in:Yes,No',
        ]);

        SettingChannelAgent::create($request->all());

        return response()->json(['success' => true, 'message' => 'Setting Channel Agent created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'menu' => 'required',
            'sub_menu' => 'required',
            'detail_menu' => 'required',
            'url' => 'required',
            'status' => 'required|in:Yes,No',
        ]);

        $settingChannelAgent = SettingChannelAgent::findOrFail($id);
        $settingChannelAgent->update($request->all());

        return response()->json(['success' => true, 'message' => 'Setting Channel Agent updated successfully.']);
    }

    public function destroy($id)
    {
        $settingChannelAgent = SettingChannelAgent::findOrFail($id);
        $settingChannelAgent->delete();

        return response()->json(['success' => true, 'message' => 'Setting Channel Agent deleted successfully.']);
    }
}
