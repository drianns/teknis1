<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardEmailController extends Controller
{
    public function index()
    {
        return view('pages.setup-channel-email.dashboard-email.index');
    }

    public function getData(Request $request)
    {
        $statistics = [
            'received' => 0,
            'response' => 0,
            'not_response' => 0,
            'queueing' => 0,
        ];

        $agentSummary = [];
        $queueing = [];

        return response()->json([
            'statistics' => $statistics,
            'agent_summary' => $agentSummary,
            'queueing' => $queueing,
        ]);
    }
}
