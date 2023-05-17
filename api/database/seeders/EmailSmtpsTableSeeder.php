<?php

namespace Database\Seeders;

use App\Models\Account;
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
        EmailSmtp::truncate();

        EmailSmtp::query()->firstOrCreate([
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

        $accounts = Account::query()->select(['company_id'])->groupBy('company_id')->get();

        EmailSmtp::withoutEvents(function () use ($accounts) {
            $i = 3;
            foreach ($accounts as $account) {
                EmailSmtp::query()->firstOrCreate([
                    'company_id' => $account->company_id,
                    'name' => 'Test smtp company ' . $i++,
                ], [
                    'member_id' => 2,
                    'security' => 'auto',
                    'host_name' => 'mailhog',
                    'from_name' => $account->company?->name ?? 'Test',
                    'from_email' => 'test@test.test',
                    'username' => '',
                    'password' => '',
                    'replay_to' => 'test@test.test',
                    'port' => 1025,
                    'is_sending_mail' => true,
                ]);
            }
        });
    }
}
