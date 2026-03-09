<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatTicketUser;
use App\Models\ChannelUser;
use App\Models\Company;
use App\Models\Channel;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        if (!$company) return;

        $channels = Channel::all();
        if ($channels->isEmpty()) return;

        $names = [
            'Budi Santoso', 'Siti Aminah', 'Agus Setiawan', 'Dewi Lestari', 'Bambang Pamungkas',
            'Rina Wijaya', 'Eko Prasetyo', 'Ani Suryani', 'Dedi Heryanto', 'Maya Sari',
            'Andi Wijaya', 'Lina Marlina', 'Rudi Tabuti', 'Siska Sukmawati', 'Hendra Gunawan'
        ];

        foreach ($names as $index => $name) {
            $customer = ChatTicketUser::create([
                'company_id' => $company->id,
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                'phone' => '0812' . str_pad($index, 8, '0', STR_PAD_LEFT)
            ]);

            // Create 1-2 channel accounts for each customer
            $numChannels = rand(1, 2);
            $randomChannels = $channels->random($numChannels);

            foreach ($randomChannels as $channel) {
                ChannelUser::create([
                    'company_id' => $company->id,
                    'channel_id' => $channel->id,
                    'chat_ticket_user_id' => $customer->id,
                    'account_id' => 'ACC-' . strtoupper(substr($channel->name, 0, 3)) . '-' . rand(1000, 9999),
                    'name' => $name
                ]);
            }
        }
    }
}
