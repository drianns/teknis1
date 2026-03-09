<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\Channel;
use App\Models\ResultTicket;
use App\Models\ChatHeaderTicket;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Company
        $company = Company::create([
            'name' => 'Kanmo Group Demo',
            'token' => 'demo-token-kanmo',
        ]);

        // 2. Users & Agents
        $admin = User::create([
            'company_id' => $company->id,
            'name' => 'Admin Kanmo',
            'email' => 'admin@kanmo.com',
            'password' => Hash::make('password123'),
        ]);

        DB::table('user_agents')->insert([
            'user_id' => $admin->id,
            'company_id' => $company->id,
            'full_name' => 'Admin Kanmo',
            'aux' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Channels
        $channels = ['WhatsApp', 'Facebook', 'Instagram', 'Email', 'Call'];
        foreach ($channels as $c) {
            Channel::create(['name' => $c]);
        }

        // 4. Result Tickets Data (Taskboard, History, etc.)
        for ($i = 1; $i <= 10; $i++) {
            ResultTicket::create([
                'company_id' => $company->id,
                'user_id' => $admin->id,
                'user_agent_id' => 1,
                'flaging' => rand(1, 4),
                'ticket_number' => 'TKT-' . date('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'status' => ['open', 'pending', 'in_progress', 'closed'][rand(0, 3)],
                'category' => 'General Inquiry',
                'subcategory' => 'Product Info',
                'payload' => json_encode(['note' => 'Demo ticket ' . $i]),
            ]);
        }

        // 5. Chat Header Tickets (Ticketing System)
        DB::table('chat_ticket_users')->insert([
            'company_id' => $company->id,
            'name' => 'Customer Demo',
            'email' => 'customer@demo.com',
            'phone' => '628123456789',
        ]);

        ChatHeaderTicket::create([
            'company_id' => $company->id,
            'chat_ticket_user_id' => 1,
            'user_agent_id' => 1,
            'ticket_number' => 'CHT-' . date('Ymd') . '-0001',
            'priority' => 'High',
            'status' => 'open',
            'subject' => 'Need help with payment',
            'category' => 'Billing',
            'question' => 'How do I pay via credit card?',
        ]);

        // 6. Channel Pages & Accounts
        DB::table('channel_pages')->insert([
            ['company_id' => $company->id, 'channel_id' => 1, 'page_id' => 'page_1', 'name' => 'Kanmo Official FB', 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $company->id, 'channel_id' => 2, 'page_id' => 'page_2', 'name' => 'Kanmo IG Store', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('channel_accounts')->insert([
            ['company_id' => $company->id, 'channel_id' => 1, 'account_id' => 'acc_1', 'name' => 'Admin FB', 'created_at' => now(), 'updated_at' => now()],
            ['company_id' => $company->id, 'channel_id' => 4, 'account_id' => 'acc_2', 'name' => 'Support Email', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
