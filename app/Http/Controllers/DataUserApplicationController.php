<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataUserApplicationController extends Controller
{
    public function index()
    {
        return view('pages.data-user-application.index');
    }
}
