<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatHeaderTicket;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // Helper: build a clean CSV string from headers + rows.
    // Uses proper RFC 4180 quoting via fputcsv into a memory stream.
    // Prepends UTF-8 BOM so Excel opens it with correct encoding.
    // ─────────────────────────────────────────────────────────────────────────
    private function buildCsv(array $headers, array $rows): string
    {
        $sep = "\t"; // Tab separator — works on all regional settings (ID/EN/etc)

        $lines = [];

        // Header row
        $lines[] = implode($sep, array_map(fn($v) => $this->tsvEscape($v), $headers));

        // Data rows
        foreach ($rows as $row) {
            $lines[] = implode($sep, array_map(fn($v) => $this->tsvEscape($v), $row));
        }

        // UTF-8 BOM so Excel opens with correct encoding
        return "\xEF\xBB\xBF" . implode("\r\n", $lines) . "\r\n";
    }

    // Escape a single cell value for tab-separated output.
    // Wraps in double-quotes only if the value contains tabs, newlines, or double-quotes.
    private function tsvEscape(mixed $value): string
    {
        $v = (string) $value;
        if (str_contains($v, '"') || str_contains($v, "\t") || str_contains($v, "\n")) {
            return '"' . str_replace('"', '""', $v) . '"';
        }
        return $v;
    }

    // Helper: return a CSV download response.
    private function csvResponse(string $csv, string $filename): \Illuminate\Http\Response
    {
        return Response::make($csv, 200, [
            'Content-Type'        => 'text/tab-separated-values; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function statisticCall()
    {
        return view('pages.report.statistic-call');
    }

    public function assignEmail(Request $request)
    {
        $query = ChatHeaderTicket::with(['userAgent.user'])
            ->whereNotNull('user_agent_id');

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
            return $this->exportAssignEmail($all);
        }

        $perPage = (int) $request->get('per_page', 10);
        $tickets = $query->paginate($perPage)->withQueryString();

        return view('pages.report.assign-email', compact('tickets'));
    }

    private function exportAssignEmail($tickets): \Illuminate\Http\Response
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

        $csv      = $this->buildCsv($headers, $rows);
        $filename = 'report_assign_email_' . now()->format('Ymd_His');
        return $this->csvResponse($csv, $filename);
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function slNespresso()
    {
        return view('pages.report.placeholder', ['title' => 'Report SL Nespresso']);
    }

    public function slKanmo()
    {
        return view('pages.report.placeholder', ['title' => 'Report SL Kanmo']);
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function baseOnSLA(Request $request)
    {
        $slaTargetMinutes = 1440;
        $query = ChatHeaderTicket::with(['userAgent.user']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $query->orderByDesc('created_at');

        $allForStats      = (clone $query)->get();
        $slaMetTotal      = $allForStats->filter(fn($t) =>
            $t->created_at && $t->created_at->diffInMinutes($t->updated_at ?? now()) <= $slaTargetMinutes
        )->count();
        $slaBreachedTotal = $allForStats->filter(fn($t) =>
            $t->created_at && $t->created_at->diffInMinutes($t->updated_at ?? now()) > $slaTargetMinutes
        )->count();
        $slaRate = $allForStats->count() > 0
            ? round(($slaMetTotal / $allForStats->count()) * 100)
            : 0;

        if ($request->filled('export')) {
            return $this->exportBaseOnSLA($allForStats, $slaTargetMinutes);
        }

        $perPage = (int) $request->get('per_page', 10);
        $tickets = $query->paginate($perPage)->withQueryString();

        return view('pages.report.base-on-sla', compact(
            'tickets', 'slaMetTotal', 'slaBreachedTotal', 'slaRate'
        ));
    }

    private function exportBaseOnSLA($tickets, int $slaTargetMinutes = 1440): \Illuminate\Http\Response
    {
        $headers = ['No', 'Ticket Number', 'Subject', 'Agent', 'Category', 'Status', 'SLA Target (min)', 'Response Time (min)', 'SLA Status'];

        $rows = $tickets->map(function ($ticket, $i) use ($slaTargetMinutes) {
            $agent = $ticket->userAgent->full_name
                ?? ($ticket->userAgent->user->name ?? 'Unassigned');
            $elapsed   = $ticket->created_at
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

        $csv      = $this->buildCsv($headers, $rows);
        $filename = 'report_base_on_sla_' . now()->format('Ymd_His');
        return $this->csvResponse($csv, $filename);
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function baseOnTransaction(Request $request)
    {
        $query = ChatHeaderTicket::with(['userAgent.user', 'chat_ticket_user']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $query->orderByDesc('created_at');

        $baseQuery      = clone $query;
        $closedCount    = (clone $baseQuery)->where('status', 'like', '%close%')->count();
        $openCount      = (clone $baseQuery)->where(function ($q) {
            $q->where('status', 'like', '%open%')->orWhere('status', 'like', '%pending%');
        })->count();
        $escalatedCount = (clone $baseQuery)->where('need_escalated', true)->count();

        if ($request->filled('export')) {
            $all = (clone $query)->get();
            return $this->exportBaseOnTransaction($all);
        }

        $perPage = (int) $request->get('per_page', 10);
        $tickets = $query->paginate($perPage)->withQueryString();

        return view('pages.report.base-on-transaction', compact(
            'tickets', 'closedCount', 'openCount', 'escalatedCount'
        ));
    }

    private function exportBaseOnTransaction($tickets): \Illuminate\Http\Response
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

        $csv      = $this->buildCsv($headers, $rows);
        $filename = 'report_base_on_transaction_' . now()->format('Ymd_His');
        return $this->csvResponse($csv, $filename);
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function baseOnStaff(Request $request)
    {
        $query = ChatHeaderTicket::with(['userAgent.user'])
            ->whereNotNull('user_agent_id');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $query->orderByDesc('created_at');

        $baseQuery    = clone $query;
        $closedCount  = (clone $baseQuery)->where('status', 'like', '%close%')->count();
        $activeAgents = (clone $baseQuery)->distinct()->count('user_agent_id');

        $timings = (clone $baseQuery)->whereNotNull('updated_at')
            ->get(['created_at', 'updated_at']);
        $avgHandleTime = $timings->count() > 0
            ? (int) $timings->avg(fn($t) => $t->created_at->diffInMinutes($t->updated_at))
            : 0;

        if ($request->filled('export')) {
            $all = (clone $query)->get();
            return $this->exportBaseOnStaff($all);
        }

        $perPage = (int) $request->get('per_page', 10);
        $tickets = $query->paginate($perPage)->withQueryString();

        return view('pages.report.base-on-staff', compact(
            'tickets', 'closedCount', 'activeAgents', 'avgHandleTime'
        ));
    }

    private function exportBaseOnStaff($tickets): \Illuminate\Http\Response
    {
        $headers = ['No', 'Agent Name', 'Layer', 'Ticket Number', 'Category', 'Sub Category', 'Priority', 'Status', 'Assigned At', 'Handle Time (min)', 'Escalated'];

        $rows = $tickets->map(function ($ticket, $i) {
            $agent     = $ticket->userAgent->full_name ?? ($ticket->userAgent->user->name ?? 'Unassigned');
            $layer     = $ticket->userAgent->layer ?? 'layer1';
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

        $csv      = $this->buildCsv($headers, $rows);
        $filename = 'report_base_on_staff_' . now()->format('Ymd_His');
        return $this->csvResponse($csv, $filename);
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function threadTransaction(Request $request)
    {
        $query = \App\Models\ChatHeader::with(['channel', 'latestTicket.userAgent.user']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $query->orderByDesc('created_at');

        $baseQuery    = clone $query;
        $closedCount  = (clone $baseQuery)->where('status', 'like', '%close%')->count();
        $openCount    = (clone $baseQuery)->where('status', 'like', '%open%')->count();
        $totalTickets = \App\Models\ChatHeaderTicket::when($request->filled('start_date'), fn($q) =>
                $q->whereDate('created_at', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn($q) =>
                $q->whereDate('created_at', '<=', $request->end_date))
            ->count();

        if ($request->filled('export')) {
            $all = (clone $query)->get();
            return $this->exportThreadTransaction($all);
        }

        $perPage = (int) $request->get('per_page', 10);
        $threads  = $query->paginate($perPage)->withQueryString();

        return view('pages.report.thread-transaction', compact(
            'threads', 'closedCount', 'openCount', 'totalTickets'
        ));
    }

    private function exportThreadTransaction($threads): \Illuminate\Http\Response
    {
        $headers = ['No', 'Thread ID', 'Channel', 'Ticket Number', 'Subject', 'Agent', 'Thread Status', 'Ticket Status', 'Created At'];

        $rows = $threads->map(function ($thread, $i) {
            $ticket  = $thread->latestTicket;
            $agent   = $ticket->userAgent->full_name ?? ($ticket->userAgent->user->name ?? 'Unassigned') ?? 'Unassigned';
            $channel = $thread->channel->name ?? ('CH-' . $thread->channel_id);
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

        $csv      = $this->buildCsv($headers, $rows);
        $filename = 'report_thread_transaction_' . now()->format('Ymd_His');
        return $this->csvResponse($csv, $filename);
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function interactionTicket(Request $request)
    {
        $query = \App\Models\ResultTicket::with(['channel', 'user_agent.user']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $query->orderByDesc('created_at');

        $base          = clone $query;
        $totalCount    = (clone $base)->count();
        $inboundCount  = (clone $base)->where('flaging', 1)->count();
        $outboundCount = (clone $base)->where('flaging', 2)->count();
        $chatCount     = (clone $base)->where('flaging', 3)->count();
        $emailCount    = (clone $base)->where('flaging', 4)->count();

        if ($request->filled('export')) {
            $all = (clone $query)->get();
            return $this->exportInteractionTicket($all);
        }

        $perPage = (int) $request->get('per_page', 10);
        $tickets  = $query->paginate($perPage)->withQueryString();

        return view('pages.report.interaction-ticket', compact(
            'tickets', 'totalCount', 'inboundCount', 'outboundCount', 'chatCount', 'emailCount'
        ));
    }

    private function exportInteractionTicket($tickets): \Illuminate\Http\Response
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

        $csv      = $this->buildCsv($headers, $rows);
        $filename = 'report_interaction_ticket_' . now()->format('Ymd_His');
        return $this->csvResponse($csv, $filename);
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function agentAux(Request $request)
    {
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

        if ($request->filled('start_date') || $request->filled('end_date')) {
            $raw = array_filter($raw, function ($r) use ($request) {
                $date = substr($r['start'], 0, 10);
                if ($request->filled('start_date') && $date < $request->start_date) return false;
                if ($request->filled('end_date')   && $date > $request->end_date)   return false;
                return true;
            });
            $raw = array_values($raw);
        }

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

        $totalAux    = count($auxData);
        $lunchCount  = collect($auxData)->where('description', 'Lunch')->count();
        $totalAgents = collect($auxData)->pluck('username')->unique()->count();

        $totalSeconds = collect($raw)->sum(function ($r) {
            return \Carbon\Carbon::parse($r['start'])->diffInSeconds(\Carbon\Carbon::parse($r['end']));
        });
        $avgSec      = $totalAux > 0 ? (int) ($totalSeconds / $totalAux) : 0;
        $avgDuration = sprintf('%02d:%02d', intdiv($avgSec, 60), $avgSec % 60);

        if ($request->filled('export')) {
            $headers = ['No', 'AUX UserName', 'AUX Description', 'AUX Start Date', 'AUX End Date', 'AUX Interval'];
            $rows    = collect($auxData)->map(fn($r, $i) => [
                $i + 1, $r['username'], $r['description'], $r['start_date'], $r['end_date'], $r['interval'],
            ])->toArray();

            $csv      = $this->buildCsv($headers, $rows);
            $filename = 'report_aux_' . now()->format('Ymd_His');
            return $this->csvResponse($csv, $filename);
        }

        return view('pages.report.agent-aux', compact(
            'auxData', 'totalAux', 'lunchCount', 'totalAgents', 'avgDuration'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function channelEmail(Request $request)
    {
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

        if ($request->filled('start_date') || $request->filled('end_date')) {
            $raw = array_values(array_filter($raw, function ($r) use ($request) {
                $date = substr($r['received'], 0, 10);
                if ($request->filled('start_date') && $date < $request->start_date) return false;
                if ($request->filled('end_date')   && $date > $request->end_date)   return false;
                return true;
            }));
        }

        $emailData = collect($raw)->map(function ($r) {
            $mins = $r['response_minutes'];
            $h    = intdiv($mins, 60);
            $m    = $mins % 60;
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

        if ($request->filled('export')) {
            $headers = ['No', 'Ticket Number', 'Subject', 'From', 'Agent', 'Status', 'Response Time', 'Received At'];
            $rows    = collect($emailData)->map(fn($r, $i) => [
                $i + 1, $r['ticket_number'], $r['subject'], $r['from'],
                $r['agent'], $r['status'], $r['response_time'], $r['received_at'],
            ])->toArray();

            $csv      = $this->buildCsv($headers, $rows);
            $filename = 'report_channel_email_' . now()->format('Ymd_His');
            return $this->csvResponse($csv, $filename);
        }

        return view('pages.report.channel-email', compact('emailData'));
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function loginActivity(Request $request)
    {
        $raw = [
            ['id' => 294, 'agent' => 'Vica Damayanti',         'description' => 'Login',  'date' => '2025-08-01 08:58:00'],
            ['id' => 293, 'agent' => 'Muhammad Ridho Fadilah', 'description' => 'Login',  'date' => '2025-08-01 09:48:00'],
            ['id' => 292, 'agent' => 'Lukas Imanuel',          'description' => 'Login',  'date' => '2025-08-01 09:02:00'],
            ['id' => 291, 'agent' => 'Firman Hadi Sanjaya',    'description' => 'Login',  'date' => '2025-08-01 10:14:00'],
            ['id' => 290, 'agent' => 'Cindy Kurnia',           'description' => 'Login',  'date' => '2025-08-01 09:06:00'],
            ['id' => 289, 'agent' => 'Ahmad Maulana',          'description' => 'Login',  'date' => '2025-08-01 09:05:00'],
            ['id' => 288, 'agent' => 'Ahmad Maulana',          'description' => 'Login',  'date' => '2025-08-01 09:51:00'],
            ['id' => 287, 'agent' => 'Budi Santoso',           'description' => 'Logout', 'date' => '2025-08-01 17:00:00'],
            ['id' => 286, 'agent' => 'Rina Wahyuni',           'description' => 'Login',  'date' => '2025-08-01 08:30:00'],
            ['id' => 285, 'agent' => 'Sari Dewi',              'description' => 'Logout', 'date' => '2025-08-01 16:45:00'],
            ['id' => 284, 'agent' => 'Dian Pratama',           'description' => 'Login',  'date' => '2025-07-31 08:15:00'],
            ['id' => 283, 'agent' => 'Hendra Gunawan',         'description' => 'Logout', 'date' => '2025-07-31 17:30:00'],
            ['id' => 282, 'agent' => 'Nanda Sari',             'description' => 'Login',  'date' => '2025-07-31 09:00:00'],
            ['id' => 281, 'agent' => 'Felix Tan',              'description' => 'Login',  'date' => '2025-07-31 08:45:00'],
            ['id' => 280, 'agent' => 'Putri Rahayu',           'description' => 'Logout', 'date' => '2025-07-31 18:00:00'],
        ];

        if ($request->filled('start_date') || $request->filled('end_date')) {
            $raw = array_values(array_filter($raw, function ($r) use ($request) {
                $date = substr($r['date'], 0, 10);
                if ($request->filled('start_date') && $date < $request->start_date) return false;
                if ($request->filled('end_date')   && $date > $request->end_date)   return false;
                return true;
            }));
        }

        $loginData = collect($raw)->map(function ($r) {
            return [
                'id'          => $r['id'],
                'agent'       => $r['agent'],
                'description' => $r['description'],
                'date'        => \Carbon\Carbon::parse($r['date'])->format('M j Y g:iA'),
                'date_raw'    => $r['date'],
            ];
        })->toArray();

        if ($request->filled('export')) {
            $headers = ['ID', 'Agent', 'Description', 'Date'];
            $rows    = collect($loginData)->map(fn($r) => [
                $r['id'], $r['agent'], $r['description'], $r['date'],
            ])->toArray();

            $csv      = $this->buildCsv($headers, $rows);
            $filename = 'report_login_activity_' . now()->format('Ymd_His');
            return $this->csvResponse($csv, $filename);
        }

        return view('pages.report.login-activity', compact('loginData'));
    }
}
