<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EmailSystemSeeder::class,
            SetupChannelEmailSeeder::class,
            EpicSystemSeeder::class,
            MasterDataSeeder::class,
            ManagementUserSeeder::class,
            MockDataSeeder::class,
            CustomerSeeder::class,
            ReportDataSeeder::class,
            SettingApplicationSeeder::class,
        ]);
    }
}
