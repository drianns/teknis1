<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Jika Anda suatu saat perlu seed ke database kosongan,
        // format datanya persis seperti baris-baris ini:
        \App\Models\MsUser::insert([
            [
                'USERID' => 2769,
                'USERNAME' => '7KA',
                'NAME' => '7KA',
                'PASSWORD' => bcrypt('Uidesk123!'),
                'ORGANIZATION' => '1087',
                'UNITKERJA' => null,
                'LEVELUSER' => 'Layer 3',
                'EMAIL_ADDRESS' => 'SUMMER.SHACK.SEMINYAK@KANMOGROUP.COM',
                'USERCREATE' => 'admin',
                'NA' => 'Y',
                'DATECREATE' => '2024-06-19 14:10:18.480',
                'EMAIL' => 0,
                'SMS' => null,
                'WHATSAPP' => 0,
                'FACEBOOK' => 0,
                'TWITTER' => 0,
                'INSTAGRAM' => 0,
                'OUTBOUND' => 0,
                'CHAT' => null,
                'INBOUND' => 0,
                'Description' => '<p>Indonesia</p>',
            ],
            [
                'USERID' => 2770,
                'USERNAME' => '9BR',
                'NAME' => '9BR',
                'PASSWORD' => bcrypt('Uidesk123!'),
                'ORGANIZATION' => '1087',
                'UNITKERJA' => null,
                'LEVELUSER' => 'Layer 3',
                'EMAIL_ADDRESS' => 'HV.BALIBRAWA@KANMOGROUP.COM',
                'USERCREATE' => 'admin',
                'NA' => 'Y',
                'DATECREATE' => '2024-06-19 14:11:13.723',
                'EMAIL' => 0,
                'SMS' => null,
                'WHATSAPP' => 0,
                'FACEBOOK' => 0,
                'TWITTER' => 0,
                'INSTAGRAM' => 0,
                'OUTBOUND' => 0,
                'CHAT' => null,
                'INBOUND' => 0,
                'Description' => '<p>Indonesia</p>',
            ],
            [
                'USERID' => 2771,
                'USERNAME' => '9BS',
                'NAME' => '9BS',
                'PASSWORD' => bcrypt('Uidesk123!'),
                'ORGANIZATION' => '1087',
                'UNITKERJA' => null,
                'LEVELUSER' => 'Layer 3',
                'EMAIL_ADDRESS' => 'HV.BASANGKASA@KANMOGROUP.COM',
                'USERCREATE' => 'admin',
                'NA' => 'Y',
                'DATECREATE' => '2024-06-19 14:25:17.970',
                'EMAIL' => 0,
                'SMS' => null,
                'WHATSAPP' => 0,
                'FACEBOOK' => 0,
                'TWITTER' => 0,
                'INSTAGRAM' => 0,
                'OUTBOUND' => 0,
                'CHAT' => null,
                'INBOUND' => 0,
                'Description' => '<p>Indonesia</p>',
            ],
        ]);
    }
}
