<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SettingAgentCallController extends Controller
{
    public function index()
    {
        // For the modal select dropdown & cards, we get users who have a role or agent assignment.
        // For demonstration given the current project scope, we just get all users (limited to 50 for performance).
        $users = User::latest()->take(50)->get();

        // Pass 'settingAgentCalls' as a list of users for now (or a specific Model if it exists,
        // but given the screenshot, the cards show user details and call type assignments).
        // Since we don't have a SettingAgentCall model defined yet, we'll map $users directly to simulate the cards.

        return view('pages.setup-channel-call.setting-agent-call.index', compact('users'));
    }
}
