<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
 public function run(): void
{
    // Kosongkan tabel sebelum diisi agar tidak duplikat
    \DB::table('msleveluser')->truncate();
    // \DB::table('uidesk_trm_aux')->truncate();

    // Baru jalankan insert (Data dari SQL Server kamu)
    \DB::table('msleveluser')->insert([
        ['LevelUserID' => 1, 'Name' => 'layer1', 'Description' => 'Layer 1', 'NA' => 'N', 'EscalationIdentity' => '1'],
        ['LevelUserID' => 2, 'Name' => 'layer2', 'Description' => 'Layer 2', 'NA' => 'N', 'EscalationIdentity' => '2'],
        ['LevelUserID' => 3, 'Name' => 'layer3', 'Description' => 'Layer 3', 'NA' => 'N', 'EscalationIdentity' => '3'],
        ['LevelUserID' => 4, 'Name' => 'Admin', 'Description' => 'Administrator', 'NA' => 'N', 'EscalationIdentity' => '4'],
        ['LevelUserID' => 5, 'Name' => 'Supervisor', 'Description' => 'Supervisor', 'NA' => 'N', 'EscalationIdentity' => '5'],
    ]);

    // \DB::table('uidesk_trm_aux')->insert([
    //     ['ID' => 24, 'Deskripsi' => 'Hold Time', 'NA' => 'Y', 'Usercreate' => 'admin', 'Datecreate' => now()],
    //     ['ID' => 3, 'Deskripsi' => 'Lunch', 'NA' => 'Y', 'Usercreate' => 'admin', 'Datecreate' => now()],
    //     ['ID' => 4, 'Deskripsi' => 'Prayer', 'NA' => 'Y', 'Usercreate' => 'admin', 'Datecreate' => now()],
    //     ['ID' => 6, 'Deskripsi' => 'Toilet', 'NA' => 'Y', 'Usercreate' => 'admin', 'Datecreate' => now()],
    //     ['ID' => 9, 'Deskripsi' => 'Ready', 'NA' => 'Y', 'Usercreate' => 'admin', 'Datecreate' => now()],
    // ]);
}
}
