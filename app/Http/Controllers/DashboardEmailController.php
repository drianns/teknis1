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
        // Mock data for demonstration purposes as models might not be fully ready
        $statistics = [
            'received' => rand(100, 500),
            'response' => rand(80, 400),
            'not_response' => rand(10, 100),
            'queueing' => rand(5, 50),
        ];

        $agentSummary = [
            ['name' => 'Adjie Sona', 'received' => 45, 'response' => 40, 'not_response' => 5],
            ['name' => 'Siti Muntaha', 'received' => 38, 'response' => 35, 'not_response' => 3],
            ['name' => 'Shifa Riani', 'received' => 52, 'response' => 50, 'not_response' => 2],
            ['name' => 'Andrean Setiawan', 'received' => 30, 'response' => 25, 'not_response' => 5],
            ['name' => 'Visa Damayanti', 'received' => 28, 'response' => 28, 'not_response' => 0],
        ];

        $queueing = [
            ['service' => 'club.indonesia@nespresso.co.id', 'from' => 'customer1@gmail.com', 'subject' => 'Coffee Machine Issue', 'date' => now()->subMinutes(5)->toDateTimeString()],
            ['service' => 'club.indonesia@nespresso.co.id', 'from' => 'user2@yahoo.com', 'subject' => 'Order Status Inquiry', 'date' => now()->subMinutes(12)->toDateTimeString()],
            ['service' => 'support@example.com', 'from' => 'tech@company.com', 'subject' => 'Bug Report #452', 'date' => now()->subMinutes(25)->toDateTimeString()],
            ['service' => 'info@company.com', 'from' => 'inquiry@tester.com', 'subject' => 'Partnership Proposal', 'date' => now()->subMinutes(40)->toDateTimeString()],
        ];

        return response()->json([
            'statistics' => $statistics,
            'agent_summary' => $agentSummary,
            'queueing' => $queueing,
        ]);
    }
}
