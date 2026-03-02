<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InboxEmailController extends Controller
{
    public function index()
    {
        return view('pages.channel.email.inbox.index');
    }

    public function markAsRead($id)
    {
        // In a real application, you would update the database here.
        // For example: Email::find($id)->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Email marked as read'
        ]);
    }

    public function moveToSpam($id)
    {
        // In a real application, you would update the database here.
        // For example: Email::find($id)->update(['folder' => 'spam']);

        return response()->json([
            'status' => 'success',
            'message' => 'Email moved to spam'
        ]);
    }
}
