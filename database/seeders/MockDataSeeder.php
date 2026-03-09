<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MockDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = \App\Models\Company::firstOrCreate(['name' => 'Kanmo Group'], [
            'token' => 'kanmo_secret_token'
        ]);

        $user = \App\Models\User::first();
        if (!$user) {
            $user = \App\Models\User::factory()->create([
                'name' => 'Admin Kanmo',
                'email' => 'admin@kanmogroup.com',
                'company_id' => $company->id
            ]);
        } else {
            $user->update(['company_id' => $company->id]);
        }

        $agent = \App\Models\UserAgent::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'full_name' => $user->name,
            'phone_number' => '08123456789',
            'aux' => 'available'
        ]);

        $customer = \App\Models\ChatTicketUser::create([
            'company_id' => $company->id,
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '0899999999'
        ]);

        for ($i = 1; $i <= 10; $i++) {
            \App\Models\ChatHeaderTicket::create([
                'company_id' => $company->id,
                'chat_ticket_user_id' => $customer->id,
                'user_agent_id' => $agent->id,
                'ticket_number' => 'TCK-' . date('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'priority' => $i % 2 == 0 ? 'high' : 'medium',
                'status' => 'open',
                'subject' => 'Support Request #' . $i,
                'category' => 'Inquiry',
                'subcategory' => 'Product Info',
                'question' => 'I have a question about product ' . $i,
                'answer' => null,
                'need_escalated' => ($i % 3 == 0),
                'source_type' => 'email'
            ]);
        }
    }
}
