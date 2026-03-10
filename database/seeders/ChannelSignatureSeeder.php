<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChannelSignature;

class ChannelSignatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ChannelSignature::truncate();

        ChannelSignature::create([
            'name' => 'Customer Relationship Center - Kanmo',
            'content' => '<strong>Customer Relationship Center</strong><br>No Telepon : 021-29181155<br>Email : support@kanmogroupp.com<br>WhatsApp : https://wa.me/622129181155<br>Senin - Minggu jam 10AM - 7PM'
        ]);

        ChannelSignature::create([
            'name' => 'Customer Relationship Center - Nespresso',
            'content' => '<strong>Customer Relationship Center</strong><br>No Telepon : 021-29181157<br>Email : club.indonesia@nespresso.co.id<br>WhatsApp : https://wa.me/622129181157<br>Senin - Minggu jam 10AM - 7PM'
        ]);
    }
}
