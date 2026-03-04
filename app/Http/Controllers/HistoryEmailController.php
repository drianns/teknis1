<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryEmailController extends Controller
{
    public function index()
    {
        $emails = collect([]);

        return view('pages.channel.email.history.index', compact('emails'));
    }
}
