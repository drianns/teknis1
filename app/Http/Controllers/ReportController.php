<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatHeaderTicket;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function statisticCall()
    {
        return view('pages.report.statistic-call');
    }

    public function assignEmail(Request $request)
    {
        $query = ChatHeaderTicket::with(['userAgent.user'])
            ->whereNotNull('user_agent_id');

        // Date filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $query->orderByDesc('created_at');

        // Handle export
        if ($request->filled('export')) {
            $all = (clone $query)->get();
            return $this->exportAssignEmail($all, $request->get('format', 'Excel'));
        }

        $perPage = (int) $request->get('per_page', 10);
        $tickets = $query->paginate($perPage)->withQueryString();

        return view('pages.report.assign-email', compact('tickets'));
    }

    /**
     * Export assign email report as CSV (Excel-compatible).
     */
    private function exportAssignEmail($tickets, string $format)
    {
        $headers = ['No', 'Ticket Number', 'Subject', 'Agent', 'Category', 'Status', 'Assigned At', 'Response Time (min)'];

        $rows = $tickets->map(function ($ticket, $i) {
            $agent = $ticket->userAgent->full_name
                ?? ($ticket->userAgent->user->name ?? 'Unassigned');

            $responseMin = $ticket->created_at
                ? (int) now()->diffInMinutes($ticket->created_at, false)
                : 0;

            return [
                $i + 1,
                $ticket->ticket_number,
                $ticket->subject ?? '',
                $agent,
                $ticket->category ?? '',
                $ticket->status ?? '',
                $ticket->created_at ? $ticket->created_at->format('d M Y H:i') : '',
                abs($responseMin),
            ];
        })->toArray();

        $filename = 'report_assign_email_' . now()->format('Ymd_His');

        $csv = implode(',', array_map('json_encode', $headers)) . "\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map('json_encode', $row)) . "\n";
        }

        $extension = match (strtolower($format)) {
            'pdf'   => 'csv', // simplified fallback — real PDF needs a package
            'csv'   => 'csv',
            default => 'csv', // Excel-compatible CSV
        };

        return Response::make($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.{$extension}\"",
        ]);
    }

    public function slNespresso()
    {
        return view('pages.report.placeholder', ['title' => 'Report SL Nespresso']);
    }

    public function slKanmo()
    {
        return view('pages.report.placeholder', ['title' => 'Report SL Kanmo']);
    }

    public function baseOnSLA(Request $request)
    {
        // SLA target in minutes (24 hours)
        $slaTargetMinutes = 1440;

        $query = ChatHeaderTicket::with(['userAgent.user']);

        // Date filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $query->orderByDesc('created_at');

        // Calculate totals for summary cards (across ALL matching records)
        $allForStats = (clone $query)->get();
        $slaMetTotal      = $allForStats->filter(fn($t) =>
            $t->created_at && $t->created_at->diffInMinutes($t->updated_at ?? now()) <= $slaTargetMinutes
        )->count();
        $slaBreachedTotal = $allForStats->filter(fn($t) =>
            $t->created_at && $t->created_at->diffInMinutes($t->updated_at ?? now()) > $slaTargetMinutes
        )->count();
        $slaRate = $allForStats->count() > 0
            ? round(($slaMetTotal / $allForStats->count()) * 100)
            : 0;

        // Handle export
        if ($request->filled('export')) {
            return $this->exportBaseOnSLA($allForStats, $request->get('format', 'Excel'), $slaTargetMinutes);
        }

        $perPage = (int) $request->get('per_page', 10);
        $tickets = $query->paginate($perPage)->withQueryString();

        return view('pages.report.base-on-sla', compact(
            'tickets', 'slaMetTotal', 'slaBreachedTotal', 'slaRate'
        ));
    }

    /**
     * Export base-on-SLA report as CSV.
     */
    private function exportBaseOnSLA($tickets, string $format, int $slaTargetMinutes = 1440)
    {
        $headers = ['No', 'Ticket Number', 'Subject', 'Agent', 'Category', 'Status', 'SLA Target (min)', 'Response Time (min)', 'SLA Status'];

        $rows = $tickets->map(function ($ticket, $i) use ($slaTargetMinutes) {
            $agent = $ticket->userAgent->full_name
                ?? ($ticket->userAgent->user->name ?? 'Unassigned');

            $elapsed = $ticket->created_at
                ? (int) $ticket->created_at->diffInMinutes($ticket->updated_at ?? now())
                : 0;
            $slaStatus = $elapsed <= $slaTargetMinutes ? 'Met' : 'Breached';

            return [
                $i + 1,
                $ticket->ticket_number,
                $ticket->subject ?? '',
                $agent,
                $ticket->category ?? '',
                $ticket->status ?? '',
                $slaTargetMinutes,
                $elapsed,
                $slaStatus,
            ];
        })->toArray();

        $filename = 'report_base_on_sla_' . now()->format('Ymd_His');

        $csv = implode(',', array_map('json_encode', $headers)) . "\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map('json_encode', $row)) . "\n";
        }

        return Response::make($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ]);
    }

    public function baseOnTransaction(Request $request)
    {
        $query = ChatHeaderTicket::with(['userAgent.user', 'chat_ticket_user']);

        // Date filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $query->orderByDesc('created_at');

        // Summary counts (efficient DB-level aggregates)
        $baseQuery     = clone $query;
        $closedCount   = (clone $baseQuery)->where('status', 'like', '%close%')->count();
        $openCount     = (clone $baseQuery)->where(function ($q) {
            $q->where('status', 'like', '%open%')->orWhere('status', 'like', '%pending%');
        })->count();
        $escalatedCount = (clone $baseQuery)->where('need_escalated', true)->count();

        // Handle export
        if ($request->filled('export')) {
            $all = (clone $query)->get();
            return $this->exportBaseOnTransaction($all, $request->get('format', 'Excel'));
        }

        $perPage = (int) $request->get('per_page', 10);
        $tickets = $query->paginate($perPage)->withQueryString();

        return view('pages.report.base-on-transaction', compact(
            'tickets', 'closedCount', 'openCount', 'escalatedCount'
        ));
    }

    /**
     * Export base-on-transaction report as CSV.
     */
    private function exportBaseOnTransaction($tickets, string $format)
    {
        $headers = ['No', 'Ticket Number', 'Customer', 'Agent', 'Category', 'Sub Category', 'Priority', 'Status', 'Created At', 'Duration (min)'];

        $rows = $tickets->map(function ($ticket, $i) {
            $agent    = $ticket->userAgent->full_name ?? ($ticket->userAgent->user->name ?? 'Unassigned');
            $customer = $ticket->chat_ticket_user->name ?? '';
            $duration = $ticket->created_at
                ? (int) $ticket->created_at->diffInMinutes($ticket->updated_at ?? now())
                : 0;

            return [
                $i + 1,
                $ticket->ticket_number,
                $customer,
                $agent,
                $ticket->category    ?? '',
                $ticket->subcategory ?? '',
                $ticket->priority    ?? '',
                $ticket->status      ?? '',
                $ticket->created_at  ? $ticket->created_at->format('d M Y H:i') : '',
                $duration,
            ];
        })->toArray();

        $filename = 'report_base_on_transaction_' . now()->format('Ymd_His');
        $csv = implode(',', array_map('json_encode', $headers)) . "\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map('json_encode', $row)) . "\n";
        }

        return Response::make($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ]);
    }


    public function baseOnStaff(Request $request)
    {
        $query = ChatHeaderTicket::with(['userAgent.user'])
            ->whereNotNull('user_agent_id');

        // Date filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $query->orderByDesc('created_at');

        // Summary stats — DB-level where possible
        $baseQuery    = clone $query;
        $closedCount  = (clone $baseQuery)->where('status', 'like', '%close%')->count();
        $activeAgents = (clone $baseQuery)->distinct()->count('user_agent_id');

        // Avg handle time (minutes) across ALL matching records
        // Pull only timestamps column to keep it light
        $timings = (clone $baseQuery)->whereNotNull('updated_at')
            ->get(['created_at', 'updated_at']);
        $avgHandleTime = $timings->count() > 0
            ? (int) $timings->avg(fn($t) => $t->created_at->diffInMinutes($t->updated_at))
            : 0;

        // Handle export
        if ($request->filled('export')) {
            $all = (clone $query)->get();
            return $this->exportBaseOnStaff($all, $request->get('format', 'Excel'));
        }

        $perPage = (int) $request->get('per_page', 10);
        $tickets = $query->paginate($perPage)->withQueryString();

        return view('pages.report.base-on-staff', compact(
            'tickets', 'closedCount', 'activeAgents', 'avgHandleTime'
        ));
    }

    /**
     * Export base-on-staff report as CSV.
     */
    private function exportBaseOnStaff($tickets, string $format)
    {
        $headers = ['No', 'Agent Name', 'Layer', 'Ticket Number', 'Category', 'Sub Category', 'Priority', 'Status', 'Assigned At', 'Handle Time (min)', 'Escalated'];

        $rows = $tickets->map(function ($ticket, $i) {
            $agent    = $ticket->userAgent->full_name ?? ($ticket->userAgent->user->name ?? 'Unassigned');
            $layer    = $ticket->userAgent->layer ?? 'layer1';
            $handleMin = $ticket->created_at
                ? (int) $ticket->created_at->diffInMinutes($ticket->updated_at ?? now())
                : 0;

            return [
                $i + 1,
                $agent,
                $layer,
                $ticket->ticket_number,
                $ticket->category    ?? '',
                $ticket->subcategory ?? '',
                $ticket->priority    ?? '',
                $ticket->status      ?? '',
                $ticket->created_at  ? $ticket->created_at->format('d M Y H:i') : '',
                $handleMin,
                $ticket->need_escalated ? 'Yes' : 'No',
            ];
        })->toArray();

        $filename = 'report_base_on_staff_' . now()->format('Ymd_His');
        $csv = implode(',', array_map('json_encode', $headers)) . "\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map('json_encode', $row)) . "\n";
        }

        return Response::make($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ]);
    }

    public function threadTransaction(Request $request)
    {
        // Import at top of query scope
        $query = \App\Models\ChatHeader::with(['channel', 'latestTicket.userAgent.user']);

        // Date filter on thread creation
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $query->orderByDesc('created_at');

        // Summary counts
        $baseQuery    = clone $query;
        $closedCount  = (clone $baseQuery)->where('status', 'like', '%close%')->count();
        $openCount    = (clone $baseQuery)->where('status', 'like', '%open%')->count();
        $totalTickets = \App\Models\ChatHeaderTicket::when($request->filled('start_date'), fn($q) =>
                $q->whereDate('created_at', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn($q) =>
                $q->whereDate('created_at', '<=', $request->end_date))
            ->count();

        // Handle export
        if ($request->filled('export')) {
            $all = (clone $query)->get();
            return $this->exportThreadTransaction($all, $request->get('format', 'Excel'));
        }

        $perPage = (int) $request->get('per_page', 10);
        $threads  = $query->paginate($perPage)->withQueryString();

        return view('pages.report.thread-transaction', compact(
            'threads', 'closedCount', 'openCount', 'totalTickets'
        ));
    }

    /**
     * Export thread-transaction report as CSV.
     */
    private function exportThreadTransaction($threads, string $format)
    {
        $headers = ['No', 'Thread ID', 'Channel', 'Ticket Number', 'Subject', 'Agent', 'Thread Status', 'Ticket Status', 'Created At'];

        $rows = $threads->map(function ($thread, $i) {
            $ticket    = $thread->latestTicket;
            $agent     = $ticket->userAgent->full_name ?? ($ticket->userAgent->user->name ?? 'Unassigned') ?? 'Unassigned';
            $channel   = $thread->channel->name ?? ('CH-' . $thread->channel_id);

            return [
                $i + 1,
                $thread->id,
                $channel,
                $ticket->ticket_number ?? '',
                $ticket->subject ?? '',
                $agent,
                $thread->status ?? '',
                $ticket->status ?? '',
                $thread->created_at ? $thread->created_at->format('d M Y H:i') : '',
            ];
        })->toArray();

        $filename = 'report_thread_transaction_' . now()->format('Ymd_His');
        $csv = implode(',', array_map('json_encode', $headers)) . "\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map('json_encode', $row)) . "\n";
        }

        return Response::make($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ]);
    }

    public function interactionTicket(Request $request)
    {
        $query = \App\Models\ResultTicket::with(['channel', 'user_agent.user']);

        // Date filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $query->orderByDesc('created_at');

        // Summary counts by flaging type (DB-level)
        $base          = clone $query;
        $totalCount    = (clone $base)->count();
        $inboundCount  = (clone $base)->where('flaging', 1)->count();
        $outboundCount = (clone $base)->where('flaging', 2)->count();
        $chatCount     = (clone $base)->where('flaging', 3)->count();
        $emailCount    = (clone $base)->where('flaging', 4)->count();

        // Handle export
        if ($request->filled('export')) {
            $all = (clone $query)->get();
            return $this->exportInteractionTicket($all, $request->get('format', 'Excel'));
        }

        $perPage = (int) $request->get('per_page', 10);
        $tickets  = $query->paginate($perPage)->withQueryString();

        return view('pages.report.interaction-ticket', compact(
            'tickets', 'totalCount', 'inboundCount', 'outboundCount', 'chatCount', 'emailCount'
        ));
    }

    /**
     * Export interaction-ticket report as CSV.
     */
    private function exportInteractionTicket($tickets, string $format)
    {
        $headers = ['No', 'Ticket Number', 'Interaction Type', 'Channel', 'Agent', 'Category', 'Sub Category', 'Status', 'Merged', 'Created At'];

        $rows = $tickets->map(function ($ticket, $i) {
            $agent   = $ticket->user_agent->full_name ?? ($ticket->user_agent->user->name ?? 'Unassigned');
            $channel = $ticket->channel->name ?? ('CH-' . $ticket->channel_id);

            return [
                $i + 1,
                $ticket->ticket_number ?? '',
                $ticket->flaging_label,
                $channel,
                $agent,
                $ticket->category    ?? '',
                $ticket->subcategory ?? '',
                $ticket->status      ?? '',
                $ticket->is_merged   ? 'Yes' : 'No',
                $ticket->created_at  ? $ticket->created_at->format('d M Y H:i') : '',
            ];
        })->toArray();

        $filename = 'report_interaction_ticket_' . now()->format('Ymd_His');
        $csv = implode(',', array_map('json_encode', $headers)) . "\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map('json_encode', $row)) . "\n";
        }

        return Response::make($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ]);
    }

    public function aux(Request $request)
    {
        // ── Dummy AUX data ─────────────────────────────────────────────────────
        $raw = [
            ['username' => 'Cindy_Kurnia',   'description' => 'Toilet',   'start' => '2025-03-28 01:39:17', 'end' => '2025-03-28 01:55:42'],
            ['username' => 'Cindy_Kurnia',   'description' => 'Lunch',    'start' => '2025-03-28 03:33:15', 'end' => '2025-03-28 04:39:00'],
            ['username' => 'Cindy_Kurnia',   'description' => 'Prayer',   'start' => '2025-03-28 06:36:39', 'end' => '2025-03-28 06:50:17'],
            ['username' => 'Budi_Santoso',   'description' => 'Break',    'start' => '2025-03-28 08:10:00', 'end' => '2025-03-28 08:25:30'],
            ['username' => 'Budi_Santoso',   'description' => 'Lunch',    'start' => '2025-03-28 12:00:00', 'end' => '2025-03-28 13:05:00'],
            ['username' => 'Budi_Santoso',   'description' => 'Meeting',  'start' => '2025-03-28 14:30:00', 'end' => '2025-03-28 15:15:45'],
            ['username' => 'Rina_Wahyuni',   'description' => 'Prayer',   'start' => '2025-03-28 04:00:00', 'end' => '2025-03-28 04:12:20'],
            ['username' => 'Rina_Wahyuni',   'description' => 'Toilet',   'start' => '2025-03-28 09:45:00', 'end' => '2025-03-28 09:55:10'],
            ['username' => 'Rina_Wahyuni',   'description' => 'Lunch',    'start' => '2025-03-28 11:30:00', 'end' => '2025-03-28 12:30:00'],
            ['username' => 'Ahmad_Fauzi',    'description' => 'Training', 'start' => '2025-03-28 07:00:00', 'end' => '2025-03-28 08:30:00'],
            ['username' => 'Ahmad_Fauzi',    'description' => 'Break',    'start' => '2025-03-28 10:15:00', 'end' => '2025-03-28 10:30:00'],
            ['username' => 'Ahmad_Fauzi',    'description' => 'Lunch',    'start' => '2025-03-28 12:30:00', 'end' => '2025-03-28 13:30:00'],
            ['username' => 'Sari_Dewi',      'description' => 'Meeting',  'start' => '2025-03-28 09:00:00', 'end' => '2025-03-28 10:00:00'],
            ['username' => 'Sari_Dewi',      'description' => 'Prayer',   'start' => '2025-03-28 12:05:00', 'end' => '2025-03-28 12:18:00'],
            ['username' => 'Sari_Dewi',      'description' => 'Lunch',    'start' => '2025-03-28 13:00:00', 'end' => '2025-03-28 14:00:00'],
            ['username' => 'Dian_Pratama',   'description' => 'Toilet',   'start' => '2025-03-28 02:20:00', 'end' => '2025-03-28 02:30:00'],
            ['username' => 'Dian_Pratama',   'description' => 'Break',    'start' => '2025-03-28 05:45:00', 'end' => '2025-03-28 06:00:00'],
            ['username' => 'Dian_Pratama',   'description' => 'Lunch',    'start' => '2025-03-28 11:00:00', 'end' => '2025-03-28 12:00:00'],
        ];

        // Apply date filter on dummy data
        if ($request->filled('start_date') || $request->filled('end_date')) {
            $raw = array_filter($raw, function ($r) use ($request) {
                $date = substr($r['start'], 0, 10);
                if ($request->filled('start_date') && $date < $request->start_date) return false;
                if ($request->filled('end_date')   && $date > $request->end_date)   return false;
                return true;
            });
            $raw = array_values($raw);
        }

        // Build formatted rows with interval
        $auxData = collect($raw)->map(function ($r) {
            $start = \Carbon\Carbon::parse($r['start']);
            $end   = \Carbon\Carbon::parse($r['end']);
            $diff  = $start->diff($end);
            return [
                'username'    => $r['username'],
                'description' => $r['description'],
                'start_date'  => $start->format('Y-m-d H:i:s'),
                'end_date'    => $end->format('Y-m-d H:i:s'),
                'interval'    => sprintf('%02d:%02d:%02d:000', $diff->h, $diff->i, $diff->s),
            ];
        })->toArray();

        // Summary stats
        $totalAux    = count($auxData);
        $lunchCount  = collect($auxData)->where('description', 'Lunch')->count();
        $totalAgents = collect($auxData)->pluck('username')->unique()->count();

        // Average duration in mm:ss
        $totalSeconds = collect($raw)->sum(function ($r) {
            return \Carbon\Carbon::parse($r['start'])->diffInSeconds(\Carbon\Carbon::parse($r['end']));
        });
        $avgSec = $totalAux > 0 ? (int) ($totalSeconds / $totalAux) : 0;
        $avgDuration = sprintf('%02d:%02d', intdiv($avgSec, 60), $avgSec % 60);

        // Handle export
        if ($request->filled('export')) {
            $headers = ['No', 'AUX UserName', 'AUX Description', 'AUX Start Date', 'AUX End Date', 'AUX Interval'];
            $rows    = collect($auxData)->map(fn($r, $i) => [
                $i + 1, $r['username'], $r['description'], $r['start_date'], $r['end_date'], $r['interval'],
            ])->toArray();

            $filename = 'report_aux_' . now()->format('Ymd_His');
            $csv = implode(',', array_map('json_encode', $headers)) . "\n";
            foreach ($rows as $row) {
                $csv .= implode(',', array_map('json_encode', $row)) . "\n";
            }

            return \Illuminate\Support\Facades\Response::make($csv, 200, [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            ]);
        }

        return view('pages.report.aux', compact(
            'auxData', 'totalAux', 'lunchCount', 'totalAgents', 'avgDuration'
        ));
    }

    public function channelEmail(Request $request)
    {
        // ── Dummy Channel Email data ────────────────────────────────────────────
        $raw = [
            ['ticket_number' => 'TKT-240301-001', 'subject' => 'Inquiry about order status',          'from' => 'customer1@gmail.com',   'agent' => 'Budi_Santoso',   'status' => 'Closed',  'received' => '2025-03-01 08:10:00', 'response_minutes' => 35],
            ['ticket_number' => 'TKT-240301-002', 'subject' => 'Return request for damaged item',     'from' => 'rina.wahyuni@yahoo.com', 'agent' => 'Rina_Wahyuni',   'status' => 'Replied', 'received' => '2025-03-01 08:45:00', 'response_minutes' => 20],
            ['ticket_number' => 'TKT-240301-003', 'subject' => 'Question about warranty policy',     'from' => 'ahmad.f@hotmail.com',   'agent' => 'Ahmad_Fauzi',    'status' => 'Open',    'received' => '2025-03-01 09:05:00', 'response_minutes' => 180],
            ['ticket_number' => 'TKT-240301-004', 'subject' => 'Complaint about late delivery',      'from' => 'saridewi@gmail.com',    'agent' => 'Sari_Dewi',      'status' => 'Pending', 'received' => '2025-03-01 09:30:00', 'response_minutes' => 300],
            ['ticket_number' => 'TKT-240301-005', 'subject' => 'Request for invoice copy',           'from' => 'dian.p@outlook.com',    'agent' => 'Dian_Pratama',   'status' => 'Closed',  'received' => '2025-03-01 10:00:00', 'response_minutes' => 15],
            ['ticket_number' => 'TKT-240301-006', 'subject' => 'Product feedback submission',        'from' => 'cindy.k@gmail.com',     'agent' => 'Cindy_Kurnia',   'status' => 'Closed',  'received' => '2025-03-01 10:20:00', 'response_minutes' => 45],
            ['ticket_number' => 'TKT-240301-007', 'subject' => 'Shipping address change request',   'from' => 'hendra99@yahoo.com',    'agent' => 'Budi_Santoso',   'status' => 'Replied', 'received' => '2025-03-01 10:55:00', 'response_minutes' => 60],
            ['ticket_number' => 'TKT-240301-008', 'subject' => 'Missing item in package',           'from' => 'lestari@gmail.com',     'agent' => 'Rina_Wahyuni',   'status' => 'Open',    'received' => '2025-03-01 11:15:00', 'response_minutes' => 240],
            ['ticket_number' => 'TKT-240301-009', 'subject' => 'Account password reset assistance', 'from' => 'wibowo.j@gmail.com',    'agent' => 'Ahmad_Fauzi',    'status' => 'Closed',  'received' => '2025-03-01 11:40:00', 'response_minutes' => 10],
            ['ticket_number' => 'TKT-240301-010', 'subject' => 'Bulk order quotation request',      'from' => 'toko.maju@email.com',   'agent' => 'Sari_Dewi',      'status' => 'Pending', 'received' => '2025-03-01 12:00:00', 'response_minutes' => 420],
            ['ticket_number' => 'TKT-240301-011', 'subject' => 'Promo code not working',            'from' => 'nanda.s@gmail.com',     'agent' => 'Dian_Pratama',   'status' => 'Replied', 'received' => '2025-03-01 13:05:00', 'response_minutes' => 30],
            ['ticket_number' => 'TKT-240301-012', 'subject' => 'Request product catalogue',         'from' => 'margareth@hotmail.com', 'agent' => 'Cindy_Kurnia',   'status' => 'Closed',  'received' => '2025-03-01 13:30:00', 'response_minutes' => 55],
            ['ticket_number' => 'TKT-240301-013', 'subject' => 'Refund status follow-up',           'from' => 'felix.tan@gmail.com',   'agent' => 'Budi_Santoso',   'status' => 'Open',    'received' => '2025-03-01 14:10:00', 'response_minutes' => 150],
            ['ticket_number' => 'TKT-240301-014', 'subject' => 'Loyalty points inquiry',            'from' => 'putri.r@yahoo.com',     'agent' => 'Rina_Wahyuni',   'status' => 'Closed',  'received' => '2025-03-01 14:45:00', 'response_minutes' => 25],
            ['ticket_number' => 'TKT-240301-015', 'subject' => 'Exchange request different size',   'from' => 'kevin.w@gmail.com',     'agent' => 'Ahmad_Fauzi',    'status' => 'Replied', 'received' => '2025-03-01 15:20:00', 'response_minutes' => 75],
        ];

        // Apply date filter
        if ($request->filled('start_date') || $request->filled('end_date')) {
            $raw = array_values(array_filter($raw, function ($r) use ($request) {
                $date = substr($r['received'], 0, 10);
                if ($request->filled('start_date') && $date < $request->start_date) return false;
                if ($request->filled('end_date')   && $date > $request->end_date)   return false;
                return true;
            }));
        }

        // Format rows
        $emailData = collect($raw)->map(function ($r) {
            $mins = $r['response_minutes'];
            $h = intdiv($mins, 60);
            $m = $mins % 60;
            return [
                'ticket_number'    => $r['ticket_number'],
                'subject'          => $r['subject'],
                'from'             => $r['from'],
                'agent'            => $r['agent'],
                'status'           => $r['status'],
                'response_minutes' => $mins,
                'response_time'    => $h > 0 ? sprintf('%dh %02dm', $h, $m) : sprintf('%dm', $m),
                'received_at'      => \Carbon\Carbon::parse($r['received'])->format('d M Y H:i'),
            ];
        })->toArray();

        // Handle export
        if ($request->filled('export')) {
            $headers = ['No', 'Ticket Number', 'Subject', 'From', 'Agent', 'Status', 'Response Time', 'Received At'];
            $filename = 'report_channel_email_' . now()->format('Ymd_His');
            $csv = implode(',', array_map('json_encode', $headers)) . "\n";
            foreach ($emailData as $i => $r) {
                $csv .= implode(',', array_map('json_encode', [
                    $i + 1, $r['ticket_number'], $r['subject'], $r['from'],
                    $r['agent'], $r['status'], $r['response_time'], $r['received_at'],
                ])) . "\n";
            }
            return \Illuminate\Support\Facades\Response::make($csv, 200, [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            ]);
        }

        return view('pages.report.channel-email', compact('emailData'));
    }

    public function loginActivity(Request $request)
    {
        // ── Dummy Login Activity data ──────────────────────────────────────────
        $raw = [
            ['id' => 294, 'agent' => 'Vica Damayanti',         'description' => 'Login', 'date' => '2025-08-01 08:58:00'],
            ['id' => 293, 'agent' => 'Muhammad Ridho Fadilah', 'description' => 'Login', 'date' => '2025-08-01 09:48:00'],
            ['id' => 292, 'agent' => 'Lukas Imanuel',          'description' => 'Login', 'date' => '2025-08-01 09:02:00'],
            ['id' => 291, 'agent' => 'Firman Hadi Sanjaya',    'description' => 'Login', 'date' => '2025-08-01 10:14:00'],
            ['id' => 290, 'agent' => 'Cindy Kurnia',           'description' => 'Login', 'date' => '2025-08-01 09:06:00'],
            ['id' => 289, 'agent' => 'Ahmad Maulana',          'description' => 'Login', 'date' => '2025-08-01 09:05:00'],
            ['id' => 288, 'agent' => 'Ahmad Maulana',          'description' => 'Login', 'date' => '2025-08-01 09:51:00'],
            ['id' => 287, 'agent' => 'Budi Santoso',           'description' => 'Logout', 'date' => '2025-08-01 17:00:00'],
            ['id' => 286, 'agent' => 'Rina Wahyuni',           'description' => 'Login',  'date' => '2025-08-01 08:30:00'],
            ['id' => 285, 'agent' => 'Sari Dewi',              'description' => 'Logout', 'date' => '2025-08-01 16:45:00'],
            ['id' => 284, 'agent' => 'Dian Pratama',           'description' => 'Login',  'date' => '2025-07-31 08:15:00'],
            ['id' => 283, 'agent' => 'Hendra Gunawan',         'description' => 'Logout', 'date' => '2025-07-31 17:30:00'],
            ['id' => 282, 'agent' => 'Nanda Sari',             'description' => 'Login',  'date' => '2025-07-31 09:00:00'],
            ['id' => 281, 'agent' => 'Felix Tan',              'description' => 'Login',  'date' => '2025-07-31 08:45:00'],
            ['id' => 280, 'agent' => 'Putri Rahayu',           'description' => 'Logout', 'date' => '2025-07-31 18:00:00'],
        ];

        // Apply date filter
        if ($request->filled('start_date') || $request->filled('end_date')) {
            $raw = array_values(array_filter($raw, function ($r) use ($request) {
                $date = substr($r['date'], 0, 10);
                if ($request->filled('start_date') && $date < $request->start_date) return false;
                if ($request->filled('end_date')   && $date > $request->end_date)   return false;
                return true;
            }));
        }

        // Format rows
        $loginData = collect($raw)->map(function ($r) {
            return [
                'id'          => $r['id'],
                'agent'       => $r['agent'],
                'description' => $r['description'],
                'date'        => \Carbon\Carbon::parse($r['date'])->format('M j Y g:iA'),
                'date_raw'    => $r['date'],
            ];
        })->toArray();

        // Handle export
        if ($request->filled('export')) {
            $headers  = ['ID', 'Agent', 'Description', 'Date'];
            $filename = 'report_login_activity_' . now()->format('Ymd_His');
            $csv = implode(',', array_map('json_encode', $headers)) . "\n";
            foreach ($loginData as $r) {
                $csv .= implode(',', array_map('json_encode', [
                    $r['id'], $r['agent'], $r['description'], $r['date'],
                ])) . "\n";
            }
            return \Illuminate\Support\Facades\Response::make($csv, 200, [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            ]);
        }

        return view('pages.report.login-activity', compact('loginData'));
    }
}
