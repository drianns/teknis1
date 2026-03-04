<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LevelUserApplicationController extends Controller
{
    public function index()
    {
        // Use dummy data since the user_applications table doesn't exist in the current schema.
        $counts = [
            'layer1' => 0,
            'layer2' => 0,
            'layer3' => 0,
            'supervisor' => 0,
            'administrator' => 0,
        ];

        return view('pages.management-user.level-user-application.index', compact('counts'));
    }

    public function getCounts()
    {
        // Use dummy data since the user_applications table doesn't exist in the current schema.
        $counts = [
            'layer1' => 0,
            'layer2' => 0,
            'layer3' => 0,
            'supervisor' => 0,
            'administrator' => 0,
        ];

        return response()->json($counts);
    }
}
