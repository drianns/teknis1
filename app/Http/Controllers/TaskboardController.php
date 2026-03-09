<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Channel;
use App\Models\ChannelPage;
use App\Models\ChannelAccount;
use App\Models\ChatHeaderTicket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskboardController extends Controller
{
    /**
     * Display the taskboard index page.
     */
    public function index(Request $request)
    {
        // Mocking company for now if not found
        $companyId = 1;
        $company = Company::find($companyId);

        // 1. Open
        $countOpen = ChatHeaderTicket::where('company_id', $companyId)
            ->where('status', 'open')
            ->count();

        // 2. Pending
        $countPending = ChatHeaderTicket::where('company_id', $companyId)
            ->where('status', 'pending')
            ->count();

        // 3. In Progress
        $countInProgress = ChatHeaderTicket::where('company_id', $companyId)
            ->whereIn('status', ['in_progress', 'process'])
            ->count();

        // 4. Closed
        $countClosed = ChatHeaderTicket::where('company_id', $companyId)
            ->whereIn('status', ['closed', 'resolved'])
            ->count();

        $cardStats = [
            'open' => $countOpen,
            'pending' => $countPending,
            'in_progress' => $countInProgress,
            'closed' => $countClosed
        ];

        return view('pages.apps.taskboard.index', compact(
            'company',
            'cardStats'
        ));
    }

    public function getData(Request $request)
    {
        $companyId = 1;

        $query = ChatHeaderTicket::with(['company', 'chat_ticket_user', 'userAgent.user'])
            ->where('company_id', $companyId);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('ticket_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('subject', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('chat_ticket_user', function($u) use ($searchTerm) {
                      $u->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status == 'in_progress') {
                $query->whereIn('status', ['in_progress', 'process']);
            } elseif ($status == 'closed') {
                $query->whereIn('status', ['closed', 'resolved']);
            } else {
                $query->where('status', $status);
            }
        }

        $perPage = $request->get('entries', 10);
        $tickets = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($tickets);
    }
}
