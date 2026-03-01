<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailMenuApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $detailMenus = [
            ['menu_name' => 'Setup Channel Email', 'sub_menu_name' => null, 'detail_menu_name' => 'Add Asset', 'url' => 'Assets_Asset.aspx?page=mst_tt&idpage=2026&idtable=2', 'type' => 'No'],
            ['menu_name' => 'Setup Channel Email', 'sub_menu_name' => null, 'detail_menu_name' => 'Allocation', 'url' => null, 'type' => 'No'],
            ['menu_name' => 'Setup Channel Email', 'sub_menu_name' => null, 'detail_menu_name' => 'Transfer Asset', 'url' => null, 'type' => 'No'],
            ['menu_name' => 'Setup Channel Email', 'sub_menu_name' => null, 'detail_menu_name' => 'Accession Detail', 'url' => null, 'type' => 'No'],
            ['menu_name' => 'Setup Channel Email', 'sub_menu_name' => null, 'detail_menu_name' => 'View Asset', 'url' => null, 'type' => 'No'],
            ['menu_name' => 'Setup Channel Email', 'sub_menu_name' => null, 'detail_menu_name' => 'Borrow Request', 'url' => 'Assets_Borrow_Request.aspx?page=mst_tt&idpage=2026&idtable=2', 'type' => 'No'],
            ['menu_name' => 'Setup Channel Email', 'sub_menu_name' => null, 'detail_menu_name' => 'Repair Request', 'url' => 'Assets_Repair_Request.aspx?page=mst_tt&idpage=2026&idtable=2', 'type' => 'No'],
            ['menu_name' => 'Setup Channel Email', 'sub_menu_name' => null, 'detail_menu_name' => 'Scrap Request', 'url' => 'Assets_Scrap_Request.aspx?page=mst_tt&idpage=2026&idtable=2', 'type' => 'No'],
            ['menu_name' => 'Setup Channel Email', 'sub_menu_name' => null, 'detail_menu_name' => 'Borrow Approve', 'url' => null, 'type' => 'No'],
            ['menu_name' => 'Setup Channel Email', 'sub_menu_name' => null, 'detail_menu_name' => 'Repair Approve', 'url' => null, 'type' => 'No'],
        ];

        foreach ($detailMenus as $menu) {
            \App\Models\DetailMenuApplication::create($menu);
        }

        // Add dummy records to force pagination UI to show up
        for ($i = 1; $i <= 50; $i++) {
            \App\Models\DetailMenuApplication::create([
                'menu_name' => 'Master Data',
                'sub_menu_name' => 'Data Type',
                'detail_menu_name' => 'Example Detail ' . $i,
                'url' => 'example_url_' . $i . '.aspx',
                'type' => $i % 2 == 0 ? 'Yes' : 'No'
            ]);
        }
    }
}
