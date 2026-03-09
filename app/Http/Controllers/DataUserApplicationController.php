<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserApplication;
use Illuminate\Support\Facades\Hash;

class DataUserApplicationController extends Controller
{
    public function index()
    {
        return view('pages.management-user.data-user-application.index');
    }

    public function getData(Request $request)
    {
        $query = UserApplication::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('user_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $data = $query->paginate($request->get('limit', 10));

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'userName' => 'required|unique:user_applications,user_name',
            'name' => 'required',
            'email' => 'required|email|unique:user_applications,email',
            'password' => 'required|min:6',
            'levelUser' => 'required',
        ]);

        $user = UserApplication::create([
            'user_name' => $request->userName,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level_user' => $request->levelUser,
            'department' => $request->department,
            'group_agent' => $request->groupAgent,
            'site' => $request->site,
            'status' => $request->status ?? 'Aktif',
            'channels' => $request->channelAgent,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = UserApplication::findOrFail($id);

        $request->validate([
            'userName' => 'required|unique:user_applications,user_name,' . $id,
            'name' => 'required',
            'email' => 'required|email|unique:user_applications,email,' . $id,
            'levelUser' => 'required',
        ]);

        $data = [
            'user_name' => $request->userName,
            'name' => $request->name,
            'email' => $request->email,
            'level_user' => $request->levelUser,
            'department' => $request->department,
            'group_agent' => $request->groupAgent,
            'site' => $request->site,
            'status' => $request->status ?? 'Aktif',
            'channels' => $request->channelAgent,
            'description' => $request->description,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function destroy($id)
    {
        $user = UserApplication::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
