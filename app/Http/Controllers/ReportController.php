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

    public function aux()
    {
        return view('pages.report.placeholder', ['title' => 'Report AUX']);
    }

    public function channelEmail()
    {
        return view('pages.report.placeholder', ['title' => 'Report Channel Email']);
    }
}
