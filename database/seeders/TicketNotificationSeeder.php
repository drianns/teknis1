<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\TicketNotificationSetting;
use App\Models\TicketNotificationUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TicketNotificationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Global Settings
        $settings = [
            ['type' => 'system', 'name' => 'Ticket Create', 'is_active' => true],
            ['type' => 'system', 'name' => 'Ticket Over SLA', 'is_active' => false],
            ['type' => 'system', 'name' => 'Ticket Close', 'is_active' => false],
            ['type' => 'system', 'name' => 'Ticket Escalation', 'is_active' => false],
            ['type' => 'customer', 'name' => 'Ticket Create', 'is_active' => true],
            ['type' => 'customer', 'name' => 'Ticket Close', 'is_active' => true],
        ];

        foreach ($settings as $setting) {
            TicketNotificationSetting::firstOrCreate(
                ['type' => $setting['type'], 'name' => $setting['name']],
                ['is_active' => $setting['is_active']]
            );
        }

        // 2. Create Dummy Users for display
        $dummyUsers = [
            ['name' => 'Rudi Salim', 'email' => 'rudi.salim@kanmo.com'],
            ['name' => 'Aftersales Service L3', 'email' => 'crckanmo@kanmoretail2.onmicrosoft.com'],
            ['name' => 'Diah Wulandari', 'email' => 'diah.wulandari@kanmogroup.com'],
            ['name' => 'Tech Team L3', 'email' => 'prod.mgt@kanmogroup.com'],
            ['name' => 'Warehouse L3', 'email' => 'admin.web@kanmogroup.com'],
            ['name' => 'Irfan Maulana assembly', 'email' => 'maulana.arif@kanmogroup.com'],
            ['name' => 'Tito Situmorang Assembly', 'email' => 'tito.situmorang@kanmogroup.com'],
            ['name' => 'Ahmad maulana Membership', 'email' => 'ahmad.maulana@kanmogroup.com'],
            ['name' => 'Vica damayanti Membership', 'email' => 'vica.damayanti@kanmogroup.com'],
            ['name' => 'Shifa Riani Membership', 'email' => 'shifa.riani@kanmogroup.com']
        ];

        foreach ($dummyUsers as $index => $u) {
            $user = User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password')
                ]
            );

            // Give them notification access
            TicketNotificationUser::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'status' => 'Yes',
                    'is_ticket_create' => true,
                    'is_ticket_over_sla' => false,
                    'is_ticket_closed' => true,
                    'is_ticket_escalation' => true,
                ]
            );
        }
    }
}
