<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SetupChannelEmailController extends Controller
{
    public function accountCorporate()
    {
        return view('pages.setup-channel-email.account-corporate.index');
    }

    public function getAccountCorporateData(Request $request)
    {
        $query = \App\Models\ChannelAccount::query();

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('perusahaan', 'like', '%' . $search . '%')
                  ->orWhere('akun', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $perPage = $request->input('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }

    public function dataSignature()
    {
        return view('pages.setup-channel-email.data-signature.index');
    }

    public function getDataSignature(Request $request)
    {
        $query = \App\Models\ChannelSignature::query();
        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
        }
        $perPage = $request->input('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }

    public function filterJumlahHari()
    {
        return view('pages.setup-channel-email.filter-jumlah-hari.index');
    }

    public function getFilterJumlahHari(Request $request)
    {
        $query = \App\Models\FilterDay::query();
        if ($request->search) {
            $search = $request->search;
            $query->where('days', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
        $perPage = $request->input('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }

    public function incomingEmail()
    {
        return view('pages.setup-channel-email.incoming-email.index');
    }

    public function getIncomingEmailData(Request $request)
    {
        $query = \App\Models\ChatHeaderTicket::where('status', 'inbound');

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', '%' . $search . '%')
                  ->orWhere('ticket_number', 'like', '%' . $search . '%');
            });
            // Note: Since name/email are in ChatTicketUser, we might need a join or relationship search
            // For now, focusing on ChatHeaderTicket direct fields.
        }

        $perPage = $request->input('per_page', 10);
        return response()->json($query->with('chat_ticket_user')->latest()->paginate($perPage));
    }

    public function jamOperasional()
    {
        return view('pages.setup-channel-email.jam-operasional.index');
    }

    public function getJamOperasional(Request $request)
    {
        $query = \App\Models\OperatingHour::query();
        if ($request->search) {
            $search = $request->search;
            $query->where('day', 'like', "%{$search}%");
        }
        $perPage = $request->input('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }

    public function settingAgent()
    {
        return view('pages.setup-channel-email.setting-agent.index');
    }

    public function getSettingAgentData(Request $request)
    {
        $query = \App\Models\UserAgent::query();

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', '%' . $search . '%')
                  ->orWhere('alias', 'like', '%' . $search . '%');
            });
        }

        $perPage = $request->input('per_page', 10);
        return response()->json($query->with('user')->latest()->paginate($perPage));
    }

    public function settingAutoReply()
    {
        return view('pages.setup-channel-email.setting-auto-reply.index');
    }

    public function getSettingAutoReply(Request $request)
    {
        $query = \App\Models\AutoReplySetting::query();
        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        $perPage = $request->input('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }

    public function templateAutoReply()
    {
        return view('pages.setup-channel-email.template-auto-reply.index');
    }

    public function getTemplateAutoReply(Request $request)
    {
        $query = \App\Models\AutoReplyTemplate::query();
        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
        }
        $perPage = $request->input('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }

    public function templateResponse()
    {
        return view('pages.setup-channel-email.template-response.index');
    }

    public function getTemplateResponse(Request $request)
    {
        $query = \App\Models\ResponseTemplate::query();
        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        }
        $perPage = $request->input('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }

    // Setting Email System
    public function emailAccounts()
    {
        return view('pages.setting-email-system.accounts.index');
    }

    public function getEmailAccountsData(Request $request)
    {
        $query = \App\Models\EmailAccount::query();
        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('incoming_server', 'like', "%{$search}%")
                  ->orWhere('outgoing_server', 'like', "%{$search}%");
        }
        $perPage = $request->input('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }

    public function emailSignature()
    {
        return view('pages.setting-email-system.signature.index');
    }

    public function getEmailSignatureData(Request $request)
    {
        $query = \App\Models\EmailSignature::query();
        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        }
        $perPage = $request->input('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }

    public function emailService()
    {
        return view('pages.setting-email-system.service.index');
    }

    public function getEmailServiceData(Request $request)
    {
        $query = \App\Models\EmailService::query();
        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        $perPage = $request->input('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }

    public function emailServiceMethod()
    {
        return view('pages.setting-email-system.service-method.index');
    }

    public function getEmailServiceMethodData(Request $request)
    {
        $query = \App\Models\EmailServiceMethod::query();
        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        $perPage = $request->input('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }

    public function serverProfile()
    {
        return view('pages.setting-email-system.server-profile.index');
    }

    public function getServerProfileData(Request $request)
    {
        $query = \App\Models\EmailServerProfile::query();
        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('ip', 'like', "%{$search}%")
                  ->orWhere('db', 'like', "%{$search}%");
        }
        $perPage = $request->input('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }

    public function serverProtocol()
    {
        return view('pages.setting-email-system.server-protocol.index');
    }

    public function getServerProtocolData(Request $request)
    {
        $query = \App\Models\EmailServerProtocol::query();
        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        $perPage = $request->input('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }

    public function serverProtocolOut()
    {
        return view('pages.setting-email-system.server-protocol-out.index');
    }

    public function getServerProtocolOutData(Request $request)
    {
        $query = \App\Models\EmailServerProtocolOut::query();
        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        $perPage = $request->input('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }

    // Setting EPIC System
    public function epicConfiguration()
    {
        return view('pages.setting-epic-system.setting-configuration-epic');
    }

    public function getEpicConfigurationData(Request $request)
    {
        $query = \App\Models\EpicConfig::query();
        if ($request->search) {
            $search = $request->search;
            $query->where('aes', 'like', "%{$search}%")
                  ->orWhere('aes_user', 'like', "%{$search}%")
                  ->orWhere('ip_db', 'like', "%{$search}%")
                  ->orWhere('db_name', 'like', "%{$search}%");
        }
        $perPage = $request->input('per_page', 10);
        return response()->json($query->latest()->paginate($perPage));
    }
}
