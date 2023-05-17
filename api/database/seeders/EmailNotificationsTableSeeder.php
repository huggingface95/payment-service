<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\EmailNotification;
use Illuminate\Database\Seeder;

class EmailNotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailNotification::query()->firstOrCreate([
            'company_id' => 1,
            'group_role_id' => 3,
            'group_type_id' => 2,
        ]);

        $accounts = Account::query()->select(['company_id'])->groupBy('company_id')->get();

        foreach ($accounts as $account) {
            EmailNotification::query()->updateOrCreate([
                'company_id' => $account->company_id,
                'group_role_id' => 3,
                'group_type_id' => 2,
            ]);
        }
    }
}
