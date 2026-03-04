<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TicketNotificationSetting;
use App\Models\TicketNotificationUser;
use App\Models\User;

class TicketNotificationSystemController extends Controller
{
    public function index()
    {
        // 1. Fetch Global Settings
        $settings = TicketNotificationSetting::all()->keyBy('name');

        // 2. Fetch User Notifications
        $notificationUsers = TicketNotificationUser::with(['user.company', 'user.userAgent'])->get();

        // 3. Fetch Master User List for Dropdown (Include their UserAgent details)
        // We will pass this JSON to the frontend for Alpine to auto-fill.
        $masterUsers = User::with('userAgent')
            ->select('id', 'name', 'email')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'level' => $user->userAgent ? $user->userAgent->user_type : 'Undefined',
                    'department' => $user->company ? $user->company->name : '-',
                    'group_agent' => '-'
                ];
            });

        return view('pages.setting-application.ticket-notification-system.index', compact('settings', 'notificationUsers', 'masterUsers'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:Yes,No'
        ]);

        $notificationUser = TicketNotificationUser::updateOrCreate(
            ['user_id' => $request->user_id],
            [
                'status' => $request->status,
                'is_ticket_create' => $request->is_ticket_create ?? false,
                'is_ticket_over_sla' => $request->is_ticket_over_sla ?? false,
                'is_ticket_closed' => $request->is_ticket_closed ?? false,
                'is_ticket_escalation' => $request->is_ticket_escalation ?? false,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Notification user saved successfully.',
            'data' => $notificationUser
        ]);
    }

    public function updateSetting(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'is_active' => 'required|boolean'
        ]);

        $setting = TicketNotificationSetting::where('name', $request->name)->first();
        if ($setting) {
            $setting->update(['is_active' => $request->is_active]);
            return response()->json(['success' => true, 'message' => 'Setting updated']);
        }

        return response()->json(['success' => false, 'message' => 'Setting not found'], 404);
    }
}
