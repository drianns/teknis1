<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AgentAuxLog;
use App\Models\LoginActivity;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;

class ReportDataSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        $user    = User::first();

        $agentName   = $user ? $user->name : 'Admin Kanmo';
        $companyId   = $company ? $company->id : null;
        $userId      = $user ? $user->id : null;

        $auxDescriptions = ['Lunch Break', 'Prayer', 'Briefing', 'Toilet', 'Break', 'Training'];

        // ── Agent AUX Logs ───────────────────────────────────────────
        $agents = ['Budi Santoso', 'Rina Wijaya', 'Eko Prasetyo', 'Siti Aminah', $agentName];

        $baseDate = Carbon::now()->subDays(7);
        for ($day = 0; $day < 7; $day++) {
            foreach ($agents as $agent) {
                $numLogs = rand(2, 5);
                $logStart = $baseDate->copy()->addDays($day)->setHour(8)->setMinute(0);
                for ($i = 0; $i < $numLogs; $i++) {
                    $start = $logStart->copy()->addMinutes($i * 90 + rand(0, 30));
                    $end   = $start->copy()->addMinutes(rand(5, 60));
                    AgentAuxLog::create([
                        'company_id'  => $companyId,
                        'user_id'     => $userId,
                        'username'    => strtolower(str_replace(' ', '.', $agent)),
                        'description' => $auxDescriptions[array_rand($auxDescriptions)],
                        'start_time'  => $start,
                        'end_time'    => $end,
                    ]);
                }
            }
        }

        // ── Login Activities ─────────────────────────────────────────
        $loginDescriptions = ['Login', 'Logout', 'Session Start', 'Password Changed', 'Profile Updated'];

        $loginBase = Carbon::now()->subDays(14);
        for ($day = 0; $day < 14; $day++) {
            foreach ($agents as $agent) {
                LoginActivity::create([
                    'user_id'     => $userId,
                    'agent'       => $agent,
                    'description' => $loginDescriptions[array_rand($loginDescriptions)],
                    'ip_address'  => '192.168.1.' . rand(1, 50),
                    'created_at'  => $loginBase->copy()->addDays($day)->addHours(rand(7, 18)),
                    'updated_at'  => now(),
                ]);
            }
        }
    }
}
