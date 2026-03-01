<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataTableCustomerController extends Controller
{
    public function index()
    {
        // Mock data for Data Table Customer
        $customers = collect([
            (object) [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+62 812-3456-7890',
                'company' => 'PT. Maju Jaya',
                'channel' => 'WhatsApp',
                'status' => 'Active',
                'created_at' => '2026-01-15 10:30:00',
            ],
            (object) [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '+62 813-9876-5432',
                'company' => 'CV. Sukses Makmur',
                'channel' => 'Email',
                'status' => 'Active',
                'created_at' => '2026-01-20 14:15:00',
            ],
            (object) [
                'id' => 3,
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@example.com',
                'phone' => '+62 821-5555-6666',
                'company' => 'PT. Teknologi Nusantara',
                'channel' => 'Telegram',
                'status' => 'Inactive',
                'created_at' => '2026-02-01 09:00:00',
            ],
            (object) [
                'id' => 4,
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nur@example.com',
                'phone' => '+62 856-7777-8888',
                'company' => 'UD. Berkah Jaya',
                'channel' => 'WhatsApp',
                'status' => 'Active',
                'created_at' => '2026-02-05 11:45:00',
            ],
            (object) [
                'id' => 5,
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'phone' => '+62 878-9999-0000',
                'company' => 'PT. Global Mandiri',
                'channel' => 'Instagram',
                'status' => 'Active',
                'created_at' => '2026-02-08 16:20:00',
            ],
        ]);

        return view('pages.data-table-customer.index', compact('customers'));
    }

    public function export()
    {
        // This will be implemented later with actual Excel export logic
        return response()->json(['message' => 'Export functionality will be implemented']);
    }
}
