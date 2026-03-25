<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MsUser;
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
        $query = MsUser::query(); // Menampilkan semua user (Aktif dan Non-Aktif)

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('NAME', 'like', '%' . $request->search . '%')
                  ->orWhere('USERNAME', 'like', '%' . $request->search . '%')
                  ->orWhere('EMAIL_ADDRESS', 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->paginate($request->get('limit', 10));

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'userName' => 'required|unique:msuser,USERNAME',
            'name' => 'required',
            'email' => 'required|email|unique:msuser,EMAIL_ADDRESS',
            'password' => 'required|min:6',
            'levelUser' => 'required',
        ]);

        $user = MsUser::create([
            'USERNAME' => $request->userName,
            'NAME' => $request->name,
            'EMAIL_ADDRESS' => $request->email,
            'PASSWORD' => Hash::make($request->password),
            'LEVELUSER' => $request->levelUser,
            'ORGANIZATION' => $request->department,
            'NA' => ($request->status == 'Non-Aktif') ? 'N' : 'Y',
            'DATECREATE' => now(),
            'Description' => $request->description,
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = MsUser::findOrFail($id);

        $request->validate([
            'userName' => 'required|unique:msuser,USERNAME,' . $id . ',USERID',
            'name' => 'required',
            'email' => 'required|email|unique:msuser,EMAIL_ADDRESS,' . $id . ',USERID',
            'levelUser' => 'required',
        ]);

        $data = [
            'USERNAME' => $request->userName,
            'NAME' => $request->name,
            'EMAIL_ADDRESS' => $request->email,
            'LEVELUSER' => $request->levelUser,
            'ORGANIZATION' => $request->department,
            'NA' => ($request->status == 'Non-Aktif') ? 'N' : 'Y',
            'Description' => $request->description,
        ];

        if ($request->filled('password')) {
            $data['PASSWORD'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }
}
