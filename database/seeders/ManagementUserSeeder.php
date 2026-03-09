<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserApplication;
use App\Models\DataAccessApplication;
use Illuminate\Support\Facades\Hash;

class ManagementUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed User Applications
        $users = [
            [
                'user_name' => 'admin_kanmo',
                'name' => 'Wanda Irwansyah',
                'email' => 'wandairwansyah@gmail.com',
                'password' => Hash::make('password123'),
                'level_user' => 'Administrator',
                'department' => null,
                'group_agent' => 'Kanmo',
                'site' => 'Jakarta',
                'status' => 'Aktif',
                'channels' => ['email', 'wa'],
                'description' => 'Super Administrator for Kanmo CRM',
                'photo_url' => 'https://ui-avatars.com/api/?name=Wanda+Irwansyah&background=0D8ABC&color=fff&size=256',
            ],
            [
                'user_name' => 'spv_crc',
                'name' => 'Supervisor CRC',
                'email' => 'spv.crc@kanmo.com',
                'password' => Hash::make('password123'),
                'level_user' => 'Supervisor',
                'department' => 'CRC',
                'group_agent' => null,
                'site' => 'Jakarta',
                'status' => 'Aktif',
                'channels' => null,
                'description' => 'Supervisor for CRC Team',
                'photo_url' => 'https://ui-avatars.com/api/?name=SPV+CRC&background=F59E0B&color=fff&size=256',
            ],
            [
                'user_name' => 'layer1_agent',
                'name' => 'Agent Layer 1',
                'email' => 'agent1@kanmo.com',
                'password' => Hash::make('password123'),
                'level_user' => 'layer1',
                'department' => null,
                'group_agent' => 'Kanmo',
                'site' => 'Jakarta',
                'status' => 'Aktif',
                'channels' => ['email', 'wa', 'inbound'],
                'description' => 'Customer Care Agent L1',
                'photo_url' => 'https://ui-avatars.com/api/?name=Agent+L1&background=10B981&color=fff&size=256',
            ],
            [
                'user_name' => 'layer2_tech',
                'name' => 'Tech Support L2',
                'email' => 'support2@kanmo.com',
                'password' => Hash::make('password123'),
                'level_user' => 'layer2',
                'department' => null,
                'group_agent' => 'Kanmo',
                'site' => 'Jakarta',
                'status' => 'Aktif',
                'channels' => ['email'],
                'description' => 'Technical Support Layer 2',
                'photo_url' => 'https://ui-avatars.com/api/?name=Tech+L2&background=3B82F6&color=fff&size=256',
            ],
            [
                'user_name' => 'layer3_finance',
                'name' => 'Finance Expert L3',
                'email' => 'finance3@kanmo.com',
                'password' => Hash::make('password123'),
                'level_user' => 'layer3',
                'department' => 'Finance',
                'group_agent' => null,
                'site' => 'Jakarta',
                'status' => 'Aktif',
                'channels' => null,
                'description' => 'Finance Department Escalation Layer 3',
                'photo_url' => 'https://ui-avatars.com/api/?name=Fin+L3&background=8B5CF6&color=fff&size=256',
            ],
        ];

        foreach ($users as $userData) {
            UserApplication::updateOrCreate(['user_name' => $userData['user_name']], $userData);
        }

        // 2. Seed Data Access Applications
        $accesses = [
            // Admin Access
            ['level_user' => 'Administrator', 'menu_level1' => 'Master Data', 'menu_level2' => null, 'menu_level3' => null, 'description' => 'Full access to all master data'],
            ['level_user' => 'Administrator', 'menu_level1' => 'Management User', 'menu_level2' => null, 'menu_level3' => null, 'description' => 'Full access to user management'],
            ['level_user' => 'Administrator', 'menu_level1' => 'Report', 'menu_level2' => null, 'menu_level3' => null, 'description' => 'Full access to configuration reports'],
            
            // Supervisor Access
            ['level_user' => 'Supervisor', 'menu_level1' => 'Apps', 'menu_level2' => 'Taskboard', 'menu_level3' => null, 'description' => 'Monitor team tasks'],
            ['level_user' => 'Supervisor', 'menu_level1' => 'Report', 'menu_level2' => null, 'menu_level3' => null, 'description' => 'View team reports'],
            
            // Layer 1 Access
            ['level_user' => 'layer1', 'menu_level1' => 'Apps', 'menu_level2' => 'Ticketing', 'menu_level3' => null, 'description' => 'Daily ticketing ops'],
            ['level_user' => 'layer1', 'menu_level1' => 'Channel', 'menu_level2' => 'Email', 'menu_level3' => 'Inbox Email', 'description' => 'Check emails'],
            
            // Layer 2 Access
            ['level_user' => 'layer2', 'menu_level1' => 'Apps', 'menu_level2' => 'Thread System', 'menu_level3' => null, 'description' => 'Handle complex cases'],
            
            // Layer 3 Access
            ['level_user' => 'layer3', 'menu_level1' => 'Apps', 'menu_level2' => 'Ticketing Department', 'menu_level3' => null, 'description' => 'Handle department escalations'],
        ];

        foreach ($accesses as $accessData) {
            $accessData['created_by'] = 'SystemSeeder';
            DataAccessApplication::create($accessData);
        }
    }
}
