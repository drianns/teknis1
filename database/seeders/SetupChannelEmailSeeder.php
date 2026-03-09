<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SetupChannelEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ChannelAccount::create([
            'company_id' => 1,
            'channel_id' => 1,
            'account_id' => 'nespresso_id_01',
            'name' => 'Nespresso Indonesia'
        ]);

        \App\Models\ChannelSignature::create([
            'name' => 'Standard Support',
            'content' => 'Terima kasih telah menghubungi kami.'
        ]);

        \App\Models\FilterDay::create(['days' => 7, 'description' => '1 Week']);
        \App\Models\FilterDay::create(['days' => 30, 'description' => '1 Month']);

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        foreach ($days as $day) {
            \App\Models\OperatingHour::create([
                'day' => $day,
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'is_active' => true
            ]);
        }

        $tpl1 = \App\Models\AutoReplyTemplate::create([
            'name' => 'Weekend Greeting',
            'subject' => 'Kami sedang libur',
            'body' => 'Terima kasih, kami akan membalas pesan Anda di hari kerja.'
        ]);

        \App\Models\AutoReplySetting::create([
            'name' => 'Weekend Auto Reply',
            'is_active' => true
        ]);

        \App\Models\ResponseTemplate::create([
            'name' => 'Greeting',
            'subject' => 'Halo dari Kanmo',
            'body' => 'Halo, ada yang bisa kami bantu?',
            'category' => 'General'
        ]);
    }
}
