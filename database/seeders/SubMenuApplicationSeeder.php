<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubMenuApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subMenus = [
            ['menu_name' => 'Master Data', 'sub_menu_name' => 'Data Type', 'url' => 'TrmCategory.aspx', 'type' => 'No'],
            ['menu_name' => 'Master Data', 'sub_menu_name' => 'Data Category', 'url' => 'TrmCategoryType.aspx', 'type' => 'No'],
            ['menu_name' => 'Master Data', 'sub_menu_name' => 'Data Meta', 'url' => 'TrmCategoryDetail.aspx', 'type' => 'No'],
            ['menu_name' => 'Master Data', 'sub_menu_name' => 'Data Sub Category', 'url' => 'TrmCategoryReason.aspx', 'type' => 'No'],
            ['menu_name' => 'Master Data', 'sub_menu_name' => 'Channel Ticket', 'url' => 'TrmChannel.aspx', 'type' => 'No'],
            ['menu_name' => 'Master Data', 'sub_menu_name' => 'Department Escalation Unit', 'url' => 'TrmOrg.aspx', 'type' => 'No'],
            ['menu_name' => 'Apps', 'sub_menu_name' => 'Taskboard', 'url' => '2_taskboard.aspx?status=Open&', 'type' => 'No'],
            ['menu_name' => 'Dashboard', 'sub_menu_name' => 'Agent', 'url' => 'Dashboard_L1.aspx', 'type' => 'No'],
            ['menu_name' => 'Dashboard', 'sub_menu_name' => 'Supervisor', 'url' => '../dashboard/spv.html', 'type' => 'Yes'],
            ['menu_name' => 'Dashboard', 'sub_menu_name' => 'Administrator', 'url' => 'Dashboard_Ticketing.aspx?', 'type' => 'No'],
        ];

        foreach ($subMenus as $subMenu) {
            \App\Models\SubMenuApplication::create($subMenu);
        }

        // Create 50 more mock records to trigger pagination display
        for ($i = 1; $i <= 50; $i++) {
            \App\Models\SubMenuApplication::create([
                'menu_name' => 'Mock Data',
                'sub_menu_name' => 'Dummy Sub Menu ' . $i,
                'url' => 'DummyUrl_' . $i . '.aspx',
                'type' => $i % 2 == 0 ? 'Yes' : 'No'
            ]);
        }
    }
}
