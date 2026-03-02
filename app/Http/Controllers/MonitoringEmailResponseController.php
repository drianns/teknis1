<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class MonitoringEmailResponseController extends Controller
{
    /**
     * Display the Monitoring Email Response page.
     */
    public function index()
    {
        return view('pages.setup-channel-email.monitoring-email-response.index');
    }

    /**
     * Get monitoring data via AJAX.
     */
    public function getData(Request $request)
    {
        // Mock data for demonstration as per requirement
        // In real application, this would query the Email/Ticket model
        $emails = [
            [
                'id' => 1,
                'email_service' => 'support@kanmogroup.com',
                'from' => 'kevin.sun@gmail.com',
                'subject' => 'Contact Form - Inquiry about product availability',
                'status' => 'Not Response',
                'agent' => 'Ahmad Bagus',
                'created_at' => Carbon::now()->subMinutes(89)->toDateTimeString(),
            ],
            [
                'id' => 2,
                'email_service' => 'support@kanmogroup.com',
                'from' => 'noreply@alpar.com',
                'subject' => 'Alpar Invoice - Pending Payment #09823',
                'status' => 'Not Response',
                'agent' => 'Ahmad Bagus',
                'created_at' => Carbon::now()->subMinutes(120)->toDateTimeString(),
            ],
            [
                'id' => 3,
                'email_service' => 'support@kanmogroup.com',
                'from' => 'noreply@resumo.com',
                'subject' => 'Resumo Semanal de Atividades',
                'status' => 'Response',
                'agent' => 'Ahmad Bagus',
                'created_at' => Carbon::now()->subMinutes(130)->toDateTimeString(),
            ],
            [
                'id' => 4,
                'email_service' => 'info@kanmogroup.com',
                'from' => 'customer.service@bank.com',
                'subject' => 'Re: Transaction Dispute - Case #7721',
                'status' => 'Response',
                'agent' => 'Siti Aminah',
                'created_at' => Carbon::now()->subHours(2)->toDateTimeString(),
            ],
            [
                'id' => 5,
                'email_service' => 'support@kanmogroup.com',
                'from' => 'john.doe@yahoo.com',
                'subject' => 'Refund Request - Order #KAN-9901',
                'status' => 'Not Response',
                'agent' => 'Budi Santoso',
                'created_at' => Carbon::now()->subHours(5)->toDateTimeString(),
            ],
        ];

        // Simple filtering mock
        if ($request->has('email_account') && $request->email_account != 'support@kanmogroup.com') {
            $emails = array_filter($emails, function ($e) use ($request) {
                return $e['email_service'] == $request->email_account;
            });
        }

        return response()->json([
            'emails' => array_values($emails),
            'pagination' => [
                'start' => 1,
                'end' => count($emails),
                'total' => count($emails),
                'totalPages' => 1,
                'currentPage' => 1,
            ]
        ]);
    }
}
