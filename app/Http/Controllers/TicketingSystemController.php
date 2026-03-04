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
use App\Models\DataCategory;
use App\Models\DataStatusTicket;
use App\Models\DataSubCategory;
use App\Models\DataType;
use App\Models\DataBrandName;
use App\Models\DataBrandCategory;
use App\Models\DataGroupName;
use App\Models\DataFulfillment;
use App\Models\DataSource;
use App\Models\DataActivity;
use App\Models\DepartmentEscalationUnit;


class TicketingSystemController extends Controller
{
    /**
     * Display the new ticketing system page.
     */
    public function index(Request $request)
    {
        // Mocking company for now
        $companyId = 1;
        $company = Company::find($companyId);

        $channels = Channel::all();
        $channel_pages = ChannelPage::where('company_id', $companyId)->get();
        $channel_accounts = ChannelAccount::where('company_id', $companyId)->get();

        $chat_ticket_statuses = DataStatusTicket::all();
        $chat_ticket_priorities = collect([]); // No DataPriority model found
        $chat_ticket_categories = DataCategory::all();

        // Additional data for form selects
        $sub_categories = DataSubCategory::all();
        $types = DataType::all();
        $brands = DataBrandName::all();
        $brand_categories = DataBrandCategory::all();
        $groups = DataGroupName::all();
        $fulfillments = DataFulfillment::all();
        $sources = DataSource::all();
        $activities = DataActivity::all();
        $escalation_units = DepartmentEscalationUnit::all();

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

        return view('pages.apps.ticketing-system.index', compact(
            'company',
            'channels',
            'channel_pages',
            'channel_accounts',
            'chat_ticket_statuses',
            'chat_ticket_categories',
            'chat_ticket_priorities',
            'group_routes',
            'ticket_history',
            'customers',
            'sub_categories',
            'types',
            'brands',
            'brand_categories',
            'groups',
            'fulfillments',
            'sources',
            'activities',
            'escalation_units'
        ));
    }
}
