<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryEmailController extends Controller
{
    public function index()
    {
        // Mock data for History Email
        $emails = collect([
            (object) [
                'id' => 1,
                'email_service' => 'support@kanmogroup.com',
                'email_address' => 'Fitiaratna@dummy.com',
                'subject' => 'Follow up: Return Request',
                'agent_name' => 'Adjie Sona',
                'date' => '2/6/2026 2:49:01 PM',
                'type' => 'OUT',
                'has_attachment' => true,
                'content' => '<p>Halo Fitiaratna,</p><p>Kami sedang memproses pengembalian dana Anda. Mohon tunggu 3-5 hari kerja.</p><p>Terima kasih.</p>',
                'attachments' => [
                    ['name' => 'invoice_refund.pdf', 'size' => '1.2 MB'],
                    ['name' => 'photo_item.jpg', 'size' => '800 KB']
                ]
            ],
            (object) [
                'id' => 2,
                'email_service' => 'support@kanmogroup.com',
                'email_address' => 'admin.web@kanmogroup.com;ifan.maulana@kanmogroup.com',
                'subject' => 'Server Maintenance Schedule',
                'agent_name' => 'Adjie Sona',
                'date' => '2/6/2026 1:53:13 PM',
                'type' => 'OUT',
                'has_attachment' => true,
                'content' => '<p>Hi Team,</p><p>Akan ada pemeliharaan server pada hari Sabtu pukul 22.00 WIB.</p>',
                'attachments' => [
                    ['name' => 'maintenance_plan.docx', 'size' => '450 KB']
                ]
            ],
            (object) [
                'id' => 3,
                'email_service' => 'cs@kanmogroup.com',
                'email_address' => 'customer.support@example.com',
                'subject' => 'Product Inquiry',
                'agent_name' => 'Siti Muntaha',
                'date' => '2/5/2026 4:22:15 PM',
                'type' => 'IN',
                'has_attachment' => false,
                'content' => '<p>Dapatkah saya menanyakan ketersediaan stok untuk sepatu lari seri GT-2000?</p>',
                'attachments' => []
            ],
            (object) [
                'id' => 4,
                'email_service' => 'support@kanmogroup.com',
                'email_address' => 'billing.team@company.com',
                'subject' => 'Invoice Payment Confirmation',
                'agent_name' => 'Shifa Riani',
                'date' => '2/5/2026 2:18:30 PM',
                'type' => 'OUT',
                'has_attachment' => true,
                'content' => '<p>Pembayaran untuk Invoice #INV-2026-001 telah dikonfirmasi.</p>',
                'attachments' => [
                    ['name' => 'receipt.png', 'size' => '1.5 MB']
                ]
            ],
            (object) [
                'id' => 5,
                'email_service' => 'info@kanmogroup.com',
                'email_address' => 'feedback@client.com',
                'subject' => 'Client Feedback',
                'agent_name' => 'Andrean Setiawan',
                'date' => '2/4/2026 11:45:22 AM',
                'type' => 'IN',
                'has_attachment' => false,
                'content' => '<p>Layanan sangat memuaskan, namun UI bisa ditingkatkan lagi.</p>',
                'attachments' => []
            ],
            (object) [
                'id' => 6,
                'email_service' => 'support@kanmogroup.com',
                'email_address' => 'warehouse.ops@kanmogroup.com;logistics@kanmogroup.com',
                'subject' => 'Shipment Status Update',
                'agent_name' => 'Visa Damayanti',
                'date' => '2/4/2026 9:12:05 AM',
                'type' => 'OUT',
                'has_attachment' => true,
                'content' => '<p>Status kiriman #SHP-992 telah berubah menjadi "Dalam Perjalanan".</p>',
                'attachments' => [
                    ['name' => 'manifest_list.pdf', 'size' => '2.1 MB']
                ]
            ],
            (object) [
                'id' => 7,
                'email_service' => 'cs@kanmogroup.com',
                'email_address' => 'complaint.dept@example.com',
                'subject' => 'Return Request',
                'agent_name' => 'Siti Muntaha',
                'date' => '2/3/2026 3:56:40 PM',
                'type' => 'IN',
                'has_attachment' => true,
                'content' => '<p>Saya ingin menukar ukuran baju yang saya beli kemarin karena terlalu kecil.</p>',
                'attachments' => [
                    ['name' => 'photo_wrong_size.jpg', 'size' => '1.1 MB']
                ]
            ],
            (object) [
                'id' => 8,
                'email_service' => 'support@kanmogroup.com',
                'email_address' => 'marketing@partner.com',
                'subject' => 'Partnership Inquiry',
                'agent_name' => 'Adjie Sona',
                'date' => '2/3/2026 10:28:15 AM',
                'type' => 'OUT',
                'has_attachment' => false,
                'content' => '<p>Kami tertarik untuk bekerja sama dalam kampanye bulan Ramadhan mendatang.</p>',
                'attachments' => []
            ],
        ]);

        return view('pages.history-email.index', compact('emails'));
    }
}
