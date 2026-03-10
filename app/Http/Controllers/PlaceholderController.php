<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlaceholderController extends Controller
{
    public function index($name)
    {
        return view('pages.placeholder', compact('name'));
    }
}
