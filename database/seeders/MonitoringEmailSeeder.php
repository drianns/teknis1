<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\UserAgent;
use App\Models\ChatTicketUser;
use App\Models\ChatHeaderTicket;
use Carbon\Carbon;

class MonitoringEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing tickets to avoid mixing old statuses (Open, Closed, etc.)
        ChatHeaderTicket::truncate();

        // 1. Ensure Company (Email Service) exists
        $companies = [
            'Kanmo Group Support' => 'support@kanmogroup.com',
            'Kanmo Info' => 'info@kanmogroup.com',
            'Kanmo Billing' => 'billing@kanmogroup.com',
        ];

        foreach ($companies as $name => $email) {
            Company::firstOrCreate(['name' => $name], [
                'token' => strtolower(str_replace(' ', '_', $name)) . '_token'
            ]);
        }

        $allCompanies = Company::all();

        // 2. Ensure some Agents exist
        $adminUser = User::first();
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin Kanmo',
                'email' => 'admin@kanmogroup.com',
                'password' => bcrypt('password'),
                'company_id' => $allCompanies->first()->id
            ]);
        }

        $agents = [
            ['name' => 'Agent Alpha', 'email' => 'alpha@kanmogroup.com'],
            ['name' => 'Agent Beta', 'email' => 'beta@kanmogroup.com'],
            ['name' => 'Agent Gamma', 'email' => 'gamma@kanmogroup.com'],
        ];

        foreach ($agents as $agentData) {
            $user = User::firstOrCreate(['email' => $agentData['email']], [
                'name' => $agentData['name'],
                'password' => bcrypt('password'),
                'company_id' => $allCompanies->random()->id
            ]);

            UserAgent::firstOrCreate(['user_id' => $user->id], [
                'company_id' => $user->company_id,
                'full_name' => $user->name,
                'phone_number' => '0812' . rand(10000000, 99999999),
                'aux' => 'available'
            ]);
        }

        $allUserAgents = UserAgent::all();

        // 3. Create some Customers items
        $customerNames = ['Budiman', 'Siti Aminah', 'Andi Wijaya', 'Dewi Lestari', 'Budi Santoso'];
        foreach ($customerNames as $index => $name) {
            ChatTicketUser::firstOrCreate(['email' => strtolower(str_replace(' ', '.', $name)) . '@gmail.com'], [
                'company_id' => $allCompanies->random()->id,
                'name' => $name,
                'phone' => '08' . rand(100000000, 999999999),
            ]);
        }

        $allCustomers = ChatTicketUser::all();

        // 4. Generate Monitoring Tickets (Incoming Emails)
        $statuses = ['Response', 'Not Response'];
        $subjects = [
            'Order Status Query',
            'Refund Request #8821',
            'Product Inquiry: Mothercare Stroller',
            'Complaint: Delayed Delivery',
            'Question about Premium Membership',
            'Return Policy Inquiry',
            'Payment Failed - Help',
            'Address Change Request'
        ];

        for ($i = 0; $i < 30; $i++) {
            $company = $allCompanies->random();
            $customer = $allCustomers->random();
            $agent = $allUserAgents->random();
            $createdAt = Carbon::now()->subDays(rand(0, 15))->subHours(rand(0, 23));

            ChatHeaderTicket::create([
                'company_id' => $company->id,
                'chat_ticket_user_id' => $customer->id,
                'user_agent_id' => $agent->id,
                'ticket_number' => 'TCK-' . $createdAt->format('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'priority' => ['low', 'medium', 'high', 'urgent'][rand(0, 3)],
                'status' => $statuses[rand(0, 1)],
                'subject' => $subjects[rand(0, count($subjects) - 1)],
                'category' => 'Customer Service',
                'subcategory' => 'General Inquiry',
                'question' => 'Sample question for ticket #' . ($i + 1),
                'source_type' => 'email',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->command->info('MonitoringEmailSeeder completed. 30 tickets created.');
    }
}
