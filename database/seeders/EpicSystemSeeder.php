<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EpicSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\EpicConfig::create([
            'aes' => '10.10.1.5',
            'aes_user' => 'aesadmin',
            'aes_pass' => '********',
            'port' => '450',
            'ip_db' => '10.10.1.6',
            'db_user' => 'epic_db',
            'db_pass' => '********',
            'db_name' => 'epic_prod',
            'dial_code' => '9',
            'call_history' => 'http://10.10.1.7/history',
            'agent_ep' => 'http://10.10.1.5:8080/agent',
            'inbound_ep' => 'http://10.10.1.5:8080/inbound',
            'outbound_ep' => 'http://10.10.1.5:8080/outbound',
            'browser_path' => 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
            'theme' => 'dark',
            'acw' => '30',
            'pbx_login' => '*10',
            'pbx_logout' => '*11',
            'pbx_aux' => '*12',
            'pbx_autoin' => '*13'
        ]);
    }
}
