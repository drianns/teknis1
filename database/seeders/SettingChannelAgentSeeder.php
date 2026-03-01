<?php

namespace Database\Seeders;

use App\Models\SettingChannelAgent;
use App\Models\User;
use Illuminate\Database\Seeder;

class SettingChannelAgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find existing users to attach the items to
        $agent1 = User::where('name', 'Agent1')->first();
        $csicon1 = User::where('name', 'csicon1')->first();

        $userId1 = $agent1 ? $agent1->id : 1;
        $userId2 = $csicon1 ? $csicon1->id : 2;

        $settings = [
            [
                'user_id' => $userId1,
                'menu' => 'File Manager',
                'sub_menu' => 'Email',
                'detail_menu' => null,
                'url' => null,
                'status' => 'Yes',
            ],
            [
                'user_id' => $userId1,
                'menu' => 'File Manager',
                'sub_menu' => 'Email',
                'detail_menu' => 'Inbox Email',
                'url' => 'TrmMailSystem.aspx',
                'status' => 'Yes',
            ],
            [
                'user_id' => $userId1,
                'menu' => 'File Manager',
                'sub_menu' => 'Email',
                'detail_menu' => 'History Email',
                'url' => 'TrmMailSystem.aspx',
                'status' => 'Yes',
            ],
            [
                'user_id' => $userId2,
                'menu' => 'File Manager',
                'sub_menu' => 'Email',
                'detail_menu' => null,
                'url' => null,
                'status' => 'Yes',
            ],
            [
                'user_id' => $userId2,
                'menu' => 'File Manager',
                'sub_menu' => 'Email',
                'detail_menu' => 'Inbox Email',
                'url' => 'TrmMailSystem.aspx',
                'status' => 'Yes',
            ],
            [
                'user_id' => $userId2,
                'menu' => 'File Manager',
                'sub_menu' => 'Email',
                'detail_menu' => 'History Email',
                'url' => 'TrmMailSystem.aspx',
                'status' => 'Yes',
            ],
            [
                'user_id' => 3, // Assuming user id 3 exists
                'menu' => 'Setup Channel Call',
                'sub_menu' => 'Outbound Call',
                'detail_menu' => null,
                'url' => 'TrxTaskboardCall.aspx',
                'status' => 'Yes',
            ],
            [
                'user_id' => 4,
                'menu' => 'Setup Channel Call',
                'sub_menu' => 'Outbound Call',
                'detail_menu' => null,
                'url' => 'TrxTaskboardCall.aspx',
                'status' => 'Yes',
            ],
        ];

        foreach ($settings as $index => $setting) {
            SettingChannelAgent::updateOrCreate(
                ['id' => $index + 96], // Matching the screenshot IDs (96, 97, 98...)
                $setting
            );
        }
    }
}
