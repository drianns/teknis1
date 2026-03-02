<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LevelUserApplicationController extends Controller
{
    public function index()
    {
        // Use dummy data since the user_applications table doesn't exist in the current schema.
        $counts = [
            'layer1' => 20,
            'layer2' => 3,
            'layer3' => 331,
            'supervisor' => 0,
            'administrator' => 2,
        ];

        return view('pages.management-user.level-user-application.index', compact('counts'));
    }

    public function getCounts()
    {
        // Use dummy data since the user_applications table doesn't exist in the current schema.
        $counts = [
            'layer1' => 20,
            'layer2' => 3,
            'layer3' => 331,
            'supervisor' => 0,
            'administrator' => 2,
        ];

        return response()->json($counts);
    }
}
