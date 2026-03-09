<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuApplication;

class MenuApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'menu_name' => 'Data User Application',
                'number' => 1,
                'url' => 'management-user.data-user-application',
                'icon' => 'bx-user-circle',
                'type' => 'Yes'
            ],

            [
                'menu_name' => 'Taskboard',
                'number' => 3,
                'url' => 'apps.taskboard',
                'icon' => 'bx-task',
                'type' => 'No'
            ],
            [
                'menu_name' => 'Ticketing System',
                'number' => 4,
                'url' => 'apps.ticketing',
                'icon' => 'bx-receipt',
                'type' => 'Yes'
            ],
            [
                'menu_name' => 'Inbox Email',
                'number' => 5,
                'url' => 'channel.email.inbox',
                'icon' => 'bx-envelope',
                'type' => 'No'
            ],
            [
                'menu_name' => 'Data Customer',
                'number' => 6,
                'url' => 'master-customer.data-customer',
                'icon' => 'bx-group',
                'type' => 'Yes'
            ]
        ];

        foreach ($menus as $menu) {
            MenuApplication::create($menu);
        }

        // Create 50 more mock records to trigger pagination display
        for ($i = 1; $i <= 50; $i++) {
            MenuApplication::create([
                'menu_name' => 'Dummy Menu ' . $i,
                'number' => 6 + $i,
                'url' => 'dummy.url.' . $i,
                'icon' => 'bx-file',
                'type' => $i % 2 == 0 ? 'Yes' : 'No'
            ]);
        }
    }
}
