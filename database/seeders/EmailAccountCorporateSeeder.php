<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChannelAccount;

class EmailAccountCorporateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ChannelAccount::truncate();

        ChannelAccount::create([
            'company_id' => 1,
            'channel_id' => 1,
            'account_id' => 'club.indonesia@nespresso.co.id',
            'name' => 'Nespresso'
        ]);

        ChannelAccount::create([
            'company_id' => 1,
            'channel_id' => 1,
            'account_id' => 'support@kanmogroup.com',
            'name' => 'Kanmo'
        ]);

        ChannelAccount::create([
            'company_id' => 1,
            'channel_id' => 1,
            'account_id' => 'uidesktest@kanmogroup.com',
            'name' => 'Kanmo'
        ]);
    }
}
