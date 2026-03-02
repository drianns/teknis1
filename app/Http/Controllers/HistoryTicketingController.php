<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryTicketingController extends Controller
{
    public function index()
    {
        // Mock data for History Ticketing
        $histories = collect([
            (object) [
                'id' => 1,
                'ticket_number' => '20260209121518957',
                'name' => 'Amalie',
                'category' => 'Complaint',
                'agent' => 'Siti Muntaha',
                'posisi' => 'Team Store',
                'sla' => '2',
                'status' => 'Open',
                'date' => '2/9/2026 12:18:18 PM',
            ],
            (object) [
                'id' => 2,
                'ticket_number' => '20260209105913909',
                'name' => 'ANGELITA',
                'category' => 'Request',
                'agent' => 'Shifa Riani',
                'posisi' => 'IT',
                'sla' => '2',
                'status' => 'Open',
                'date' => '2/9/2026 10:58:13 AM',
            ],
            (object) [
                'id' => 3,
                'ticket_number' => '20260209103655900',
                'name' => 'Ami',
                'category' => 'Complaint',
                'agent' => 'Siti Muntaha',
                'posisi' => 'Warehouse',
                'sla' => '3',
                'status' => 'Open',
                'date' => '2/9/2026 10:36:55 AM',
            ],
            (object) [
                'id' => 4,
                'ticket_number' => '20260208055603186',
                'name' => 'Mu\'tashim Billah',
                'category' => 'Complaint',
                'agent' => 'Andrean Setiawan',
                'posisi' => 'Warehouse',
                'sla' => '3',
                'status' => 'In Progress',
                'date' => '2/8/2026 5:56:03 PM',
            ],
            (object) [
                'id' => 5,
                'ticket_number' => '20260208013919127',
                'name' => 'Silvia anggraini',
                'category' => 'Complaint',
                'agent' => 'Andrean Setiawan',
                'posisi' => 'Warehouse',
                'sla' => '3',
                'status' => 'Open',
                'date' => '2/8/2026 1:38:19 PM',
            ],
            (object) [
                'id' => 6,
                'ticket_number' => '20260208103911961',
                'name' => 'Johana',
                'category' => 'Complaint',
                'agent' => 'Visa Damayanti',
                'posisi' => 'CRC Kanmo',
                'sla' => '2',
                'status' => 'Pending',
                'date' => '2/8/2026 10:39:11 AM',
            ],
        ]);

        return view('pages.apps.history-ticketing.index', compact('histories'));
    }
}
