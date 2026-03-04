<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecordingController extends Controller
{
    /**
     * Display the recording index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $items = collect([]);
        
        return view('pages.recording.index', compact('items'));
    }
}
