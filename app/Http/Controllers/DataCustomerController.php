<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataCustomerController extends Controller
{
    public function index()
    {
        // Mock data for customer list
        $customers = collect([
            (object) [
                'id' => 1,
                'name' => 'Ira',
                'email' => 'ira.lestari@gmail.com',
                'phone' => '6281213290018',
                'member_id' => 'MBR001',
                'avatar' => 'https://ui-avatars.com/api/?name=Ira&background=4F46E5&color=fff',
                'additional_contacts' => [
                    (object) ['channel' => 'Email', 'account' => 'ira.work@company.com', 'status' => 'Active', 'user_create' => 'Admin', 'date_create' => '2026-01-15'],
                    (object) ['channel' => 'WhatsApp', 'account' => '6281298765432', 'status' => 'Active', 'user_create' => 'Admin', 'date_create' => '2026-01-20'],
                ],
                'transactions' => [
                    (object) ['ticket_number' => 'TKT-2026-001', 'category' => 'Technical Support', 'status' => 'Open', 'user_create' => 'Admin', 'date_create' => '2026-02-08 10:30:00'],
                    (object) ['ticket_number' => 'TKT-2026-004', 'category' => 'Complaint', 'status' => 'Resolved', 'user_create' => 'Support', 'date_create' => '2026-02-05 14:20:00'],
                ],
            ],
            (object) [
                'id' => 2,
                'name' => 'Lita',
                'email' => 'lita.12345@gmail.com',
                'phone' => '6281248810542',
                'member_id' => 'MBR002',
                'avatar' => 'https://ui-avatars.com/api/?name=Lita&background=06B6D4&color=fff',
                'additional_contacts' => [
                    (object) ['channel' => 'Telegram', 'account' => '@lita_official', 'status' => 'Active', 'user_create' => 'Support', 'date_create' => '2026-01-18'],
                ],
                'transactions' => [
                    (object) ['ticket_number' => 'TKT-2026-002', 'category' => 'Billing', 'status' => 'In Progress', 'user_create' => 'Admin', 'date_create' => '2026-02-07 14:20:00'],
                ],
            ],
            (object) [
                'id' => 3,
                'name' => 'dumm',
                'email' => 'dumm2224@dummy.com',
                'phone' => '6281190026620',
                'member_id' => 'MBR003',
                'avatar' => 'https://ui-avatars.com/api/?name=Dumm&background=8B5CF6&color=fff',
                'additional_contacts' => [],
                'transactions' => [],
            ],
            (object) [
                'id' => 4,
                'name' => 'Najla',
                'email' => 'najla@gmail.com',
                'phone' => '6281212080518',
                'member_id' => 'MBR004',
                'avatar' => 'https://ui-avatars.com/api/?name=Najla&background=EC4899&color=fff',
                'additional_contacts' => [
                    (object) ['channel' => 'Email', 'account' => 'najla.business@email.com', 'status' => 'Active', 'user_create' => 'Admin', 'date_create' => '2026-02-01'],
                    (object) ['channel' => 'Email', 'account' => 'najla.personal@gmail.com', 'status' => 'Inactive', 'user_create' => 'Admin', 'date_create' => '2026-01-10'],
                ],
                'transactions' => [
                    (object) ['ticket_number' => 'TKT-2026-003', 'category' => 'Complaint', 'status' => 'Resolved', 'user_create' => 'Support Team', 'date_create' => '2026-02-06 09:15:00'],
                    (object) ['ticket_number' => 'TKT-2026-005', 'category' => 'Technical Support', 'status' => 'Open', 'user_create' => 'Admin', 'date_create' => '2026-02-04 11:45:00'],
                ],
            ],
            (object) [
                'id' => 5,
                'name' => 'simon',
                'email' => 'simon.paul@gmail.com',
                'phone' => '6281180976290',
                'member_id' => 'MBR005',
                'avatar' => 'https://ui-avatars.com/api/?name=Simon&background=10B981&color=fff',
                'additional_contacts' => [],
                'transactions' => [
                    (object) ['ticket_number' => 'TKT-2026-006', 'category' => 'Billing', 'status' => 'Closed', 'user_create' => 'Admin', 'date_create' => '2026-02-03 16:30:00'],
                ],
            ],
            (object) [
                'id' => 6,
                'name' => 'Ghita',
                'email' => 'ghita.cantik@gmail.com',
                'phone' => '6281298765432',
                'member_id' => 'MBR006',
                'avatar' => 'https://ui-avatars.com/api/?name=Ghita&background=F59E0B&color=fff',
                'additional_contacts' => [
                    (object) ['channel' => 'WhatsApp', 'account' => '6281234567890', 'status' => 'Active', 'user_create' => 'Support', 'date_create' => '2026-01-25'],
                    (object) ['channel' => 'Instagram', 'account' => '@ghita_official', 'status' => 'Active', 'user_create' => 'Admin', 'date_create' => '2026-02-05'],
                ],
                'transactions' => [],
            ],
        ]);

        return view('pages.data-customer.index', compact('customers'));
    }
}
