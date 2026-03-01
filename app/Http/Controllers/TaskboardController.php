<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Channel;
use App\Models\ChannelPage;
use App\Models\ChannelAccount;
use App\Models\ResultTicket;
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
        $company = Company::find($companyId) ?? new Company(['id' => 1, 'name' => 'Kanmo Group']);

        // 1. Open
        $countOpen = ResultTicket::where('company_id', $companyId)
            ->where('status', 'open')
            ->count();

        // 2. Pending
        $countPending = ResultTicket::where('company_id', $companyId)
            ->where('status', 'pending')
            ->count();

        // 3. In Progress
        $countInProgress = ResultTicket::where('company_id', $companyId)
            ->whereIn('status', ['in_progress', 'process'])
            ->count();

        // 4. Closed
        $countClosed = ResultTicket::where('company_id', $companyId)
            ->whereIn('status', ['closed', 'resolved'])
            ->count();

        $cardStats = [
            'open' => $countOpen,
            'pending' => $countPending,
            'in_progress' => $countInProgress,
            'closed' => $countClosed
        ];

        // Query for Table
        $resultTicketsQuery = ResultTicket::with([
            'company',
        ])->where('company_id', $companyId);

        // Simple Search Filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $resultTicketsQuery->where(function ($q) use ($searchTerm) {
                $q->where('ticket_number', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status == 'in_progress') {
                $resultTicketsQuery->whereIn('status', ['in_progress', 'process']);
            } elseif ($status == 'closed') {
                $resultTicketsQuery->whereIn('status', ['closed', 'resolved']);
            } else {
                $resultTicketsQuery->where('status', $status);
            }
        }

        $perPage = $request->input('entries', 10);
        $resultTickets = $resultTicketsQuery->orderBy('created_at', 'desc')->paginate($perPage);

        // Transform collection to add mocked data matching the reference image
        $resultTickets->getCollection()->transform(function ($ticket) {
            // Mock SLA between 1-3 days
            $ticket->sla = rand(1, 3);

            // Mock Note SLA
            $daysPassed = $ticket->created_at->diffInDays(now());
            $ticket->note_sla = $daysPassed > $ticket->sla
                ? ($daysPassed - $ticket->sla) . " Days Over"
                : ($ticket->sla - $daysPassed) . " Days Later";

            // Mock Department based on some logic or random
            $departments = ['Warehouse', 'Logistics', 'IT', 'Finance', 'HR'];
            $ticket->department = $departments[array_rand($departments)];

            // Mock Customer Name if missing or static 'Vidya'
            // Ensure extra_data is an object
            if (is_string($ticket->extra_data)) {
                $ticket->extra_data = json_decode($ticket->extra_data);
            }
            if (!$ticket->extra_data) {
                $ticket->extra_data = (object) [];
            }

            // Force mock name for demo purposes to prove dynamic data
            $names = ['Advan', 'Sarah', 'Budi', 'Dewi', 'Michael', 'Jessica', 'David', 'Putri'];
            $randomName = $names[array_rand($names)];
            // Append last 4 digits of ticket number to make it look unique
            $suffix = substr($ticket->ticket_number, -4);
            $ticket->extra_data->name = "$randomName ($suffix)";

            return $ticket;
        });

        return view('pages.taskboard.index', compact(
            'company',
            'cardStats',
            'resultTickets'
        ));
    }
}
