<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataAccessApplicationController extends Controller
{
    public function index()
    {
        return view('pages.management-user.data-access-application.index');
    }
}
