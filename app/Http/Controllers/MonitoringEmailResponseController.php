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
        $emailServices = \App\Models\Company::all();
        return view('pages.setup-channel-email.monitoring-email-response.index', compact('emailServices'));
    }

    /**
     * Get monitoring data via AJAX.
     */
    public function getData(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $emailAccount = $request->input('email_account');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = \App\Models\ChatHeaderTicket::with(['chat_ticket_user', 'userAgent', 'company'])
            ->where('source_type', 'email');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%$search%")
                    ->orWhere('ticket_number', 'like', "%$search%")
                    ->orWhereHas('chat_ticket_user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%");
                    });
            });
        }

        if ($emailAccount) {
            // Assuming emailAccount corresponds to Company name (Email Service)
            $query->whereHas('company', function ($q) use ($emailAccount) {
                $q->where('name', $emailAccount);
            });
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $paginated = $query->latest()->paginate($perPage);

        return response()->json([
            'emails' => collect($paginated->items())->map(function ($item) {
                return [
                    'id' => $item->id,
                    'email_service' => $item->company ? $item->company->name : 'N/A',
                    'from' => $item->chat_ticket_user ? $item->chat_ticket_user->name : 'Unknown',
                    'email' => $item->chat_ticket_user ? $item->chat_ticket_user->email : '',
                    'subject' => $item->subject,
                    'ticket_number' => $item->ticket_number,
                    'status' => ucfirst($item->status),
                    'agent' => $item->userAgent ? $item->userAgent->full_name : '-',
                    'created_at' => $item->created_at->toIso8601String()
                ];
            }),
            'pagination' => [
                'current_page' => $paginated->currentPage(),
                'totalPages' => $paginated->lastPage(),
                'total' => $paginated->total(),
                'start' => ($paginated->currentPage() - 1) * $paginated->perPage() + 1,
                'end' => min($paginated->currentPage() * $paginated->perPage(), $paginated->total())
            ]
        ]);
    }
}
