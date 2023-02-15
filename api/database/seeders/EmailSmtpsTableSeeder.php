<?php

namespace Database\Seeders;

use App\Models\EmailSmtp;
use Illuminate\Database\Seeder;

class EmailSmtpsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailSmtp::firstOrCreate([
            'id' => 1,
            'member_id' => 2,
            'security' => 'ssl',
            'host_name' => 'mail.lavachange.com',
            'from_name' => 'docutest',
            'from_email' => 'test@lavachange.com',
            'username' => 'test@lavachange.com',
            'password' => 'test@test@123',
            'replay_to' => 'test@lavachange.com',
            'port' => 465,
            'company_id' => 1,
            'is_sending_mail' => true,
            'name' => 'Test smtp',
        ]);

        EmailSmtp::firstOrCreate([
            'id' => 2,
            'member_id' => 3,
            'security' => 'ssl',
            'host_name' => 'mail.lavachange.com',
            'from_name' => 'docutest',
            'from_email' => 'test@lavachange.com',
            'username' => 'test@lavachange.com',
            'password' => 'test@test@123',
            'replay_to' => 'test@lavachange.com',
            'port' => 465,
            'company_id' => 1,
            'is_sending_mail' => true,
            'name' => 'Test smtp 2',
        ]);
    }
}
