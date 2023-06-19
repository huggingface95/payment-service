<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\CompanyRevenueAccount;
use Illuminate\Database\Seeder;

class CompanyRevenueAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accounts = Account::where('company_id', 1)->limit(5)->get();

        foreach ($accounts as $account) {
            $number = sprintf('%s%s%s', strtoupper($account->currencies?->code), substr('0000000000', 0, -strlen($account->company_id)), $account->company_id);
            CompanyRevenueAccount::updateOrCreate([
                'number' => $number,
                'company_id' => 1,
                'currency_id' => $account->currency_id,
            ]);
        }
    }
}
