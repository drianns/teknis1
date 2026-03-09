<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatHeaderTicket;
use App\Models\AgentAuxLog;
use App\Models\LoginActivity;
use Illuminate\Support\Facades\Response;

use App\Exports\GenericReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    // Helper: Perform a stylized Excel (.xlsx) download.
    private function performExport(array $headers, array $rows, string $filename): BinaryFileResponse
    {
        return Excel::download(
            new GenericReportExport($headers, $rows, $filename, 'Data'),
            "{$filename}_" . now()->format('Ymd_His') . ".xlsx"
        );
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

    private function exportAssignEmail($tickets): BinaryFileResponse
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

        return $this->performExport($headers, $rows, 'report_assign_email');
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

    private function exportBaseOnSLA($tickets, int $slaTargetMinutes = 1440): BinaryFileResponse
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

        return $this->performExport($headers, $rows, 'report_base_on_sla');
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

    private function exportBaseOnTransaction($tickets): BinaryFileResponse
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

        return $this->performExport($headers, $rows, 'report_base_on_transaction');
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

    private function exportBaseOnStaff($tickets): BinaryFileResponse
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

        return $this->performExport($headers, $rows, 'report_base_on_staff');
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

    private function exportThreadTransaction($threads): BinaryFileResponse
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

        return $this->performExport($headers, $rows, 'report_thread_transaction');
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

    private function exportInteractionTicket($tickets): BinaryFileResponse
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

        return $this->performExport($headers, $rows, 'report_interaction_ticket');
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function agentAux(Request $request)
    {
        $query = AgentAuxLog::query();

        if ($request->filled('start_date')) {
            $query->whereDate('start_time', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('start_time', '<=', $request->end_date);
        }

        $query->orderByDesc('start_time');

        $allLogs = (clone $query)->get();

        $auxData = $allLogs->map(function ($r) {
            $diff = $r->start_time->diff($r->end_time);
            return [
                'username'    => $r->username,
                'description' => $r->description,
                'start_date'  => $r->start_time->format('Y-m-d H:i:s'),
                'end_date'    => $r->end_time->format('Y-m-d H:i:s'),
                'interval'    => sprintf('%02d:%02d:%02d:000', $diff->h, $diff->i, $diff->s),
            ];
        })->toArray();

        $totalAux    = count($auxData);
        $lunchCount  = $allLogs->whereIn('description', ['Lunch Break', 'Lunch'])->count();
        $totalAgents = $allLogs->pluck('username')->unique()->count();
        $totalSeconds = $allLogs->sum('duration_seconds');
        $avgSec      = $totalAux > 0 ? (int) ($totalSeconds / $totalAux) : 0;
        $avgDuration = sprintf('%02d:%02d', intdiv($avgSec, 60), $avgSec % 60);

        if ($request->filled('export')) {
            $headers = ['No', 'AUX UserName', 'AUX Description', 'AUX Start Date', 'AUX End Date', 'AUX Interval'];
            $rows    = collect($auxData)->map(fn($r, $i) => [
                $i + 1, $r['username'], $r['description'], $r['start_date'], $r['end_date'], $r['interval'],
            ])->toArray();

            return $this->performExport($headers, $rows, 'report_aux');
        }

        return view('pages.report.agent-aux', compact(
            'auxData', 'totalAux', 'lunchCount', 'totalAgents', 'avgDuration'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function channelEmail(Request $request)
    {
        $query = ChatHeaderTicket::with(['userAgent.user', 'chat_ticket_user'])
            ->where('source_type', 'email');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $query->orderByDesc('created_at');

        $allTickets = (clone $query)->get();

        $emailData = $allTickets->map(function ($t) {
            $agent = $t->userAgent->full_name ?? ($t->userAgent->user->name ?? 'Unassigned');
            $from  = $t->chat_ticket_user->email ?? '-';
            $mins  = $t->created_at ? (int) $t->created_at->diffInMinutes($t->updated_at ?? now()) : 0;
            $h     = intdiv($mins, 60);
            $m     = $mins % 60;
            return [
                'ticket_number'    => $t->ticket_number,
                'subject'          => $t->subject ?? '-',
                'from'             => $from,
                'agent'            => $agent,
                'status'           => $t->status ?? '-',
                'response_minutes' => $mins,
                'response_time'    => $h > 0 ? sprintf('%dh %02dm', $h, $m) : sprintf('%dm', $m),
                'received_at'      => $t->created_at ? $t->created_at->format('d M Y H:i') : '-',
            ];
        })->toArray();

        if ($request->filled('export')) {
            $headers = ['No', 'Ticket Number', 'Subject', 'From', 'Agent', 'Status', 'Response Time', 'Received At'];
            $rows    = collect($emailData)->map(fn($r, $i) => [
                $i + 1, $r['ticket_number'], $r['subject'], $r['from'],
                $r['agent'], $r['status'], $r['response_time'], $r['received_at'],
            ])->toArray();

            return $this->performExport($headers, $rows, 'report_channel_email');
        }

        return view('pages.report.channel-email', compact('emailData'));
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function loginActivity(Request $request)
    {
        $query = LoginActivity::query();

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $query->orderByDesc('created_at');

        $allLogs = (clone $query)->get();

        $loginData = $allLogs->map(function ($r) {
            return [
                'id'          => $r->id,
                'agent'       => $r->agent,
                'description' => $r->description,
                'date'        => $r->created_at->format('M j Y g:iA'),
                'date_raw'    => $r->created_at->toDateTimeString(),
            ];
        })->toArray();

        if ($request->filled('export')) {
            $headers = ['ID', 'Agent', 'Description', 'Date'];
            $rows    = collect($loginData)->map(fn($r) => [
                $r['id'], $r['agent'], $r['description'], $r['date'],
            ])->toArray();

            return $this->performExport($headers, $rows, 'report_login_activity');
        }

        return view('pages.report.login-activity', compact('loginData'));
    }
}
