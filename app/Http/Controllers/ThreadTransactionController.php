<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThreadTransactionController extends Controller
{
    public function index(Request $request)
    {
        $allTransactions = collect([]);

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

        return view('pages.apps.thread-transaction.index', compact('transactions', 'stats'));
    }
}
