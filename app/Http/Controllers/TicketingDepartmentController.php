<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketingDepartmentController extends Controller
{
    public function index(Request $request)
    {
        // Mock Data based on the screenshot provided
        $tickets = collect([
            (object) [
                'id' => '22550',
                'ticket_number' => '20260209121518957',
                'name' => 'Amalie',
                'kategori' => 'Complaint',
                'department' => 'Team Store',
                'sla' => '2',
                'agent' => 'Siti Muntama',
                'status' => 'Open',
                'created_at' => '2/9/2026 12:15:18 PM',
            ],
            (object) [
                'id' => '22547',
                'ticket_number' => '20260209105913909',
                'name' => 'ANGELITA',
                'kategori' => 'Request',
                'department' => 'IT',
                'sla' => '2',
                'agent' => 'Shifa Riani',
                'status' => 'Open',
                'created_at' => '2/9/2026 10:59:13 AM',
            ],
            (object) [
                'id' => '22546',
                'ticket_number' => '20260209103655900',
                'name' => 'Ami',
                'kategori' => 'Complaint',
                'department' => 'Warehouse',
                'sla' => '3',
                'agent' => 'Siti Muntama',
                'status' => 'Open',
                'created_at' => '2/9/2026 10:36:55 AM',
            ],
            (object) [
                'id' => '22542',
                'ticket_number' => '20260208055603186',
                'name' => 'Mu\'tashim Billah',
                'kategori' => 'Complaint',
                'department' => 'Warehouse',
                'sla' => '3',
                'agent' => 'Andrean Setiawan',
                'status' => 'In progress',
                'created_at' => '2/8/2026 5:56:03 PM',
            ],
            (object) [
                'id' => '22541',
                'ticket_number' => '20260208013919127',
                'name' => 'Silvia anggraini',
                'kategori' => 'Complaint',
                'department' => 'Warehouse',
                'sla' => '3',
                'agent' => 'Andrean Setiawan',
                'status' => 'Open',
                'created_at' => '2/8/2026 1:39:19 PM',
            ],
            (object) [
                'id' => '22534',
                'ticket_number' => '20260208103911961',
                'name' => 'Johana',
                'kategori' => 'Complaint',
                'department' => 'CRC Kanmo',
                'sla' => '2',
                'agent' => 'Vica Damayanti',
                'status' => 'Pending',
                'created_at' => '2/8/2026 10:39:11 AM',
            ],
            (object) [
                'id' => '22531',
                'ticket_number' => '20260207072821410',
                'name' => 'Alfa',
                'kategori' => 'Request',
                'department' => 'CX Ops',
                'sla' => '2',
                'agent' => 'Vica Damayanti',
                'status' => 'In progress',
                'created_at' => '2/7/2026 7:28:21 PM',
            ],
            (object) [
                'id' => '22529',
                'ticket_number' => '20260207060557710',
                'name' => 'Natasha Dwi F',
                'kategori' => 'Complaint',
                'department' => 'Warehouse',
                'sla' => '3',
                'agent' => 'Rifna Mitriana',
                'status' => 'In progress',
                'created_at' => '2/7/2026 6:05:57 PM',
            ],
            (object) [
                'id' => '22518',
                'ticket_number' => '20260207112237094',
                'name' => 'Erni',
                'kategori' => 'Complaint',
                'department' => 'Finance',
                'sla' => '14',
                'agent' => 'Siti Muntama',
                'status' => 'In progress',
                'created_at' => '2/7/2026 11:22:37 AM',
            ],
            (object) [
                'id' => '22508',
                'ticket_number' => '20260206061105320',
                'name' => 'boris',
                'kategori' => 'Complaint',
                'department' => 'Warehouse',
                'sla' => '3',
                'agent' => 'Shifa Riani',
                'status' => 'In progress',
                'created_at' => '2/6/2026 6:11:05 PM',
            ],
        ]);

        return view('pages.ticketing-department.index', compact('tickets'));
    }
}
