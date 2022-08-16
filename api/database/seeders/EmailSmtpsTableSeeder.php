<?php

namespace Database\Seeders;

use App\Models\EmailSmtp;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmailSmtpsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailSmtp::insert([
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
    }
}
