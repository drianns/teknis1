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
        $items = collect([
            (object)[
                'unique_id' => '1740974415.12345',
                'call_date' => '2026-03-02 11:40:15',
                'ticket_number' => 'T-20260302-001',
                'disposition' => 'Resolved',
                'customer' => 'John Doe',
                'agent' => 'Agent Alpha',
                'duration' => '00:05:23',
                'recording_file' => 'REC-12345.mp3',
                'stt' => 'Success',
                'qa' => '95/100'
            ]
        ]);
        
        return view('pages.recording.index', compact('items'));
    }
}
