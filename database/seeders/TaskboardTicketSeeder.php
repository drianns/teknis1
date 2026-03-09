<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatHeaderTicket;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaskboardTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure a basic Company ID=1 exists
        $company = \App\Models\Company::firstOrCreate(
            ['id' => 1],
            ['name' => 'Kanmo Dummy']
        );

        $customer = \App\Models\ChatTicketUser::firstOrCreate(
            ['email' => 'guest@kanmo.com'],
            ['name' => 'Guest Customer', 'phone' => '081234567890']
        );

        $user = \App\Models\User::firstOrCreate(
            ['email' => 'agent.starter@kanmo.com'],
            ['name' => 'Agent Starter', 'password' => bcrypt('password123')]
        );

        $userAgent = \App\Models\UserAgent::firstOrCreate(
            ['user_id' => $user->id],
            ['company_id' => 1, 'full_name' => 'Agent Starter', 'aux' => 'available']
        );

        $companyId = $company->id;
        $customerId = $customer->id;
        $userAgentId = $userAgent->id;

        // 4. Populate Tickets
        $statuses = ['open', 'pending', 'in_progress', 'closed'];
        $categories = ['Kendala Transaksi', 'Informasi Produk', 'Pengiriman', 'Lain-lain'];
        
        for ($i = 1; $i <= 30; $i++) {
            ChatHeaderTicket::create([
                'company_id' => 1,
                'chat_ticket_user_id' => $customerId,
                'user_agent_id' => $userAgentId,
                'ticket_number' => 'TKT-2026-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'subject' => 'Dummy Ticket Issue ' . $i,
                'category' => $categories[array_rand($categories)],
                'subcategory' => 'Detail ' . $i,
                'question' => 'Tolong bantu saya dengan masalah ini.',
                'answer' => '-',
                'status' => $statuses[array_rand($statuses)],
                'priority' => 'Normal',
                'source_type' => 'call',
                'need_escalated' => 0,
                'created_at' => Carbon::now()->subDays(rand(0, 3))->subHours(rand(0, 10)),
            ]);
        }
    }
}
