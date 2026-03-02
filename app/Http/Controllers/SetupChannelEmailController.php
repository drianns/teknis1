<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SetupChannelEmailController extends Controller
{
    public function accountCorporate()
    {
        return view('pages.setup-channel-email.account-corporate.index');
    }

    public function dataSignature()
    {
        return view('pages.setup-channel-email.data-signature.index');
    }

    public function filterJumlahHari()
    {
        return view('pages.setup-channel-email.filter-jumlah-hari.index');
    }

    public function incomingEmail()
    {
        return view('pages.setup-channel-email.incoming-email.index');
    }

    public function jamOperasional()
    {
        return view('pages.setup-channel-email.jam-operasional.index');
    }

    public function settingAgent()
    {
        return view('pages.setup-channel-email.setting-agent.index');
    }

    public function settingAutoReply()
    {
        return view('pages.setup-channel-email.setting-auto-reply.index');
    }

    public function templateAutoReply()
    {
        return view('pages.setup-channel-email.template-auto-reply.index');
    }

    public function templateResponse()
    {
        return view('pages.setup-channel-email.template-response.index');
    }

    // Setting Email System
    public function emailAccounts()
    {
        return view('pages.setting-email-system.accounts.index');
    }

    public function emailSignature()
    {
        return view('pages.setting-email-system.signature.index');
    }

    public function emailService()
    {
        return view('pages.setting-email-system.service.index');
    }

    public function emailServiceMethod()
    {
        return view('pages.setting-email-system.service-method.index');
    }

    public function serverProfile()
    {
        return view('pages.setting-email-system.server-profile.index');
    }

    public function serverProtocol()
    {
        return view('pages.setting-email-system.server-protocol.index');
    }

    public function serverProtocolOut()
    {
        return view('pages.setting-email-system.server-protocol-out.index');
    }

    // Setting EPIC System
    public function epicConfiguration()
    {
        return view('pages.setting-epic-system.setting-configuration-epic');
    }
}
