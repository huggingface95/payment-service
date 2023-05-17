<?php

namespace Database\Seeders;

use App\Models\Transactions;
use Illuminate\Database\Seeder;

class TransactionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Transactions::withoutEvents(function () {
            for ($i = 1; $i <= 20; $i++) {
                $transaction = Transactions::factory()->definition();
                $transaction['transfer_id'] = $i;

                Transactions::query()->firstOrCreate($transaction);
            }
        });
    }
}
