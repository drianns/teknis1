<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Channel;
use App\Models\Company;
use App\Models\ChannelPage;
use App\Models\ChannelAccount;
use App\Models\ChatHeaderTicket;
use App\Models\ChatTicketUser;
use App\Models\ChannelUser;

class TicketingSystemController extends Controller
{
    /**
     * Display the new ticketing system page.
     */
    public function index(Request $request)
    {
        // Mocking company for now
        $companyId = 1;
        $company = Company::find($companyId) ?? new Company(['id' => 1, 'name' => 'Kanmo Group']);

        $channels = Channel::all();
        $channel_pages = ChannelPage::where('company_id', $companyId)->get();
        $channel_accounts = ChannelAccount::where('company_id', $companyId)->get();

        $chat_ticket_statuses = [
            (object) ['id' => 1, 'name' => 'Open'],
            (object) ['id' => 2, 'name' => 'Pending'],
            (object) ['id' => 3, 'name' => 'Resolved'],
            (object) ['id' => 4, 'name' => 'Closed'],
        ];
        $chat_ticket_priorities = [
            (object) ['id' => 1, 'name' => 'Low'],
            (object) ['id' => 2, 'name' => 'Medium'],
            (object) ['id' => 3, 'name' => 'High'],
            (object) ['id' => 4, 'name' => 'Urgent'],
        ];
        $chat_ticket_categories = [
            (object) ['id' => 1, 'name' => 'Inquiry'],
            (object) ['id' => 2, 'name' => 'Complaint'],
            (object) ['id' => 3, 'name' => 'Request'],
            (object) ['id' => 4, 'name' => 'Feedback'],
        ];
        $group_routes = [];

        // Fetch Ticket History with Filter & Pagination
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = ChatHeaderTicket::with(['chat_ticket_user'])
            ->where('company_id', $companyId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhereHas('chat_ticket_user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $ticket_history = $query->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'history_page')
            ->withQueryString();

        // Fetch Customer Data (ChannelUser) with Filter & Pagination (Tab 3)
        $perPageCustomer = $request->input('per_page_customer', 10);
        $searchCustomer = $request->input('search_customer');

        $customerQuery = ChannelUser::with(['channel', 'chat_ticket_user'])
            ->where('company_id', $companyId);

        if ($searchCustomer) {
            $customerQuery->where(function ($q) use ($searchCustomer) {
                $q->where('name', 'like', "%{$searchCustomer}%")
                    ->orWhere('email', 'like', "%{$searchCustomer}%")
                    ->orWhere('account_id', 'like', "%{$searchCustomer}%");
            });
        }

        $customers = $customerQuery->orderBy('created_at', 'desc')
            ->paginate($perPageCustomer, ['*'], 'customer_page')
            ->withQueryString();

        return view('pages.ticketing-system.index', compact(
            'company',
            'channels',
            'channel_pages',
            'channel_accounts',
            'chat_ticket_statuses',
            'chat_ticket_categories',
            'chat_ticket_priorities',
            'group_routes',
            'ticket_history',
            'customers'
        ));
    }
}
