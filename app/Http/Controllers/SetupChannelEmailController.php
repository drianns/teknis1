<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SetupChannelEmailController extends Controller
{
    public function accountCorporate()
    {
        $rows = \App\Models\ChannelAccount::paginate(10);
        return view('pages.setup-channel-email.account-corporate.index', compact('rows'));
    }

    public function dataSignature()
    {
        // Placeholder for ChannelSignature model
        $rows = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        return view('pages.setup-channel-email.data-signature.index', compact('rows'));
    }

    public function filterJumlahHari()
    {
        $rows = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        return view('pages.setup-channel-email.filter-jumlah-hari.index', compact('rows'));
    }

    public function incomingEmail()
    {
        $rows = \App\Models\ChatHeaderTicket::where('status', 'inbound')->paginate(10);
        return view('pages.setup-channel-email.incoming-email.index', compact('rows'));
    }

    public function jamOperasional()
    {
        $rows = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        return view('pages.setup-channel-email.jam-operasional.index', compact('rows'));
    }

    public function settingAgent()
    {
        $rows = \App\Models\UserAgent::paginate(10);
        return view('pages.setup-channel-email.setting-agent.index', compact('rows'));
    }

    public function settingAutoReply()
    {
        $rows = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        return view('pages.setup-channel-email.setting-auto-reply.index', compact('rows'));
    }

    public function templateAutoReply()
    {
        $rows = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        return view('pages.setup-channel-email.template-auto-reply.index', compact('rows'));
    }

    public function templateResponse()
    {
        $rows = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        return view('pages.setup-channel-email.template-response.index', compact('rows'));
    }

    // Setting Email System
    public function emailAccounts()
    {
        $rows = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        return view('pages.setting-email-system.accounts.index', compact('rows'));
    }

    public function emailSignature()
    {
        $rows = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        return view('pages.setting-email-system.signature.index', compact('rows'));
    }

    public function emailService()
    {
        $rows = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        return view('pages.setting-email-system.service.index', compact('rows'));
    }

    public function emailServiceMethod()
    {
        $rows = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        return view('pages.setting-email-system.service-method.index', compact('rows'));
    }

    public function serverProfile()
    {
        $rows = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        return view('pages.setting-email-system.server-profile.index', compact('rows'));
    }

    public function serverProtocol()
    {
        $rows = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        return view('pages.setting-email-system.server-protocol.index', compact('rows'));
    }

    public function serverProtocolOut()
    {
        $rows = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        return view('pages.setting-email-system.server-protocol-out.index', compact('rows'));
    }

    // Setting EPIC System
    public function epicConfiguration()
    {
        $configs = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        return view('pages.setting-epic-system.setting-configuration-epic', compact('configs'));
    }
}
