<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sig1 = \App\Models\EmailSignature::create([
            'name' => 'Default Corporate',
            'email' => 'support@kanmogroup.com',
            'phone' => '+62 21 1234567',
            'body' => 'Regards, Kanmo Group Support Team'
        ]);

        $sig2 = \App\Models\EmailSignature::create([
            'name' => 'Nespresso Support',
            'email' => 'club.indonesia@nespresso.co.id',
            'phone' => '1500-498',
            'body' => 'Sincerely, Nespresso Indonesia'
        ]);

        $svc1 = \App\Models\EmailService::create(['name' => 'Gmail']);
        $svc2 = \App\Models\EmailService::create(['name' => 'Outlook']);
        $svc3 = \App\Models\EmailService::create(['name' => 'Custom SMTP']);

        $meth1 = \App\Models\EmailServiceMethod::create(['name' => 'OAuth2']);
        $meth2 = \App\Models\EmailServiceMethod::create(['name' => 'Password']);

        $prof1 = \App\Models\EmailServerProfile::create([
            'name' => 'Production Server',
        ]);

        $prot1 = \App\Models\EmailServerProtocol::create(['name' => 'IMAP']);
        $prot2 = \App\Models\EmailServerProtocol::create(['name' => 'POP3']);

        $protOut = \App\Models\EmailServerProtocolOut::create(['name' => 'SMTP']);

        \App\Models\EmailAccount::create([
            'name' => 'Nespresso Official',
            'incoming_user' => 'club.indonesia@nespresso.co.id',
            'incoming_pass' => '********',
            'incoming_server' => 'imap.gmail.com',
            'incoming_port' => '993',
            'encrypted_connection' => 'ssl',
            'outgoing_user' => 'club.indonesia@nespresso.co.id',
            'outgoing_pass' => '********',
            'outgoing_server' => 'smtp.gmail.com',
            'outgoing_port' => '465',
            'encrypted_connection_out' => 'ssl',
            'server_protocol' => 'IMAP',
            'server_protocol_out' => 'SMTP',
            'server_profile_id' => $prof1->id,
            'email_signature_id' => $sig2->id,
            'email_service_method_id' => $meth1->id,
            'status' => 'active'
        ]);
    }
}
