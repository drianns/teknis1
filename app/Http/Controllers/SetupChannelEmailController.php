<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SetupChannelEmailController extends Controller
{
    public function accountCorporate()
    {
        return view('pages.account-corporate.index');
    }

    public function dataSignature()
    {
        return view('pages.data-signature.index');
    }

    public function filterJumlahHari()
    {
        return view('pages.filter-jumlah-hari.index');
    }

    public function incomingEmail()
    {
        return view('pages.incoming-email.index');
    }

    public function jamOperasional()
    {
        return view('pages.jam-operasional.index');
    }

    public function settingAgent()
    {
        return view('pages.setting-agent.index');
    }

    public function settingAutoReply()
    {
        return view('pages.setting-auto-reply.index');
    }

    public function templateAutoReply()
    {
        return view('pages.template-auto-reply.index');
    }

    public function templateResponse()
    {
        return view('pages.template-response.index');
    }

    // Setting Email System
    public function emailAccounts()
    {
        return view('pages.setting-email-accounts.index');
    }

    public function emailSignature()
    {
        return view('pages.setting-email-signature.index');
    }

    public function emailService()
    {
        return view('pages.setting-email-service.index');
    }

    public function emailServiceMethod()
    {
        return view('pages.setting-email-service-method.index');
    }

    public function serverProfile()
    {
        return view('pages.setting-server-profile.index');
    }

    public function serverProtocol()
    {
        return view('pages.setting-server-protocol.index');
    }

    public function serverProtocolOut()
    {
        return view('pages.setting-server-protocol-out.index');
    }

    // Setting EPIC System
    public function epicConfiguration()
    {
        return view('pages.setting-epic-system.setting-configuration-epic');
    }
}
