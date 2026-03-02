<?php

namespace App\Http\Controllers;

use App\Models\SettingChannelAgent;
use App\Models\User;
use Illuminate\Http\Request;

class SettingChannelAgentController extends Controller
{
    public function index(Request $request)
    {
        $query = SettingChannelAgent::with('user');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhere('menu', 'like', '%' . $search . '%')
                ->orWhere('sub_menu', 'like', '%' . $search . '%')
                ->orWhere('detail_menu', 'like', '%' . $search . '%')
                ->orWhere('url', 'like', '%' . $search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $settingChannelAgents = $query->paginate($perPage);
        $users = User::all();

        return view('pages.setting-application.setting-channel-agent.index', compact('settingChannelAgents', 'users'));
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

        return redirect()->route('setting.channel.agent.index')->with('success', 'Setting Channel Agent created successfully.');
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

        return redirect()->route('setting.channel.agent.index')->with('success', 'Setting Channel Agent updated successfully.');
    }

    public function destroy($id)
    {
        $settingChannelAgent = SettingChannelAgent::findOrFail($id);
        $settingChannelAgent->delete();

        return redirect()->route('setting.channel.agent.index')->with('success', 'Setting Channel Agent deleted successfully.');
    }
}
