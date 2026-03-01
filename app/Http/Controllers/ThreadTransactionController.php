<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThreadTransactionController extends Controller
{
    public function index(Request $request)
    {
        // Mock Data untuk keperluan UI Demo
        $allTransactions = collect([
            (object) [
                'type' => 'call',
                'channel' => 'call',
                'name' => 'Budi Santoso',
                'number_id' => '6281234567890',
                'account' => 'User123',
                'subject' => 'Komplain Jaringan',
                'agent' => 'Agent A',
                'created_at' => now()->subMinutes(10),
            ],
            (object) [
                'type' => 'chat',
                'channel' => 'whatsapp',
                'name' => 'Dewi Lestari',
                'number_id' => '6285555555555',
                'account' => 'User999',
                'subject' => 'Konfirmasi Pembayaran',
                'agent' => 'Agent C',
                'created_at' => now()->subMinutes(30),
            ],
            (object) [
                'type' => 'chat',
                'channel' => 'facebook',
                'name' => 'Siti Aminah',
                'number_id' => 'FB_987654321',
                'account' => 'User456',
                'subject' => 'Tanya Promo',
                'agent' => 'Agent B',
                'created_at' => now()->subHours(1),
            ],
            (object) [
                'type' => 'chat',
                'channel' => 'instagram',
                'name' => 'Rudi Hartono',
                'number_id' => 'IG_user_rudi',
                'account' => 'User789',
                'subject' => 'Kendala Login',
                'agent' => 'Agent A',
                'created_at' => now()->subHours(2),
            ],
        ]);

        // Calculate Stats for Cards
        $stats = [
            'call' => $allTransactions->where('channel', 'call')->count(),
            'whatsapp' => $allTransactions->where('channel', 'whatsapp')->count(),
            'facebook' => $allTransactions->where('channel', 'facebook')->count(),
            'instagram' => $allTransactions->where('channel', 'instagram')->count(),
        ];

        // Filter Logic
        $transactions = $allTransactions;
        if ($request->has('channel') && $request->channel != '') {
            $transactions = $allTransactions->where('channel', $request->channel);
        }

        return view('pages.thread-transaction.index', compact('transactions', 'stats'));
    }
}
