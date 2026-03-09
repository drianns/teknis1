<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserApplication;

class LevelUserApplicationController extends Controller
{
    public function index()
    {
        $counts = $this->getInternalCounts();
        return view('pages.management-user.level-user-application.index', compact('counts'));
    }

    public function getCounts()
    {
        return response()->json($this->getInternalCounts());
    }

    private function getInternalCounts()
    {
        return [
            'layer1' => UserApplication::where('level_user', 'layer1')->count(),
            'layer2' => UserApplication::where('level_user', 'layer2')->count(),
            'layer3' => UserApplication::where('level_user', 'layer3')->count(),
            'supervisor' => UserApplication::where('level_user', 'Supervisor')->count(),
            'administrator' => UserApplication::where('level_user', 'Administrator')->count(),
        ];
    }
}
