<?php

namespace Database\Seeders;

use App\Models\PaymentProviderIban;
use Illuminate\Database\Seeder;

class PaymentProviderIbanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            PaymentProviderIban::firstOrCreate(
                [
                    'name' => 'PaymentProviderIBAN '.$i,
                ],
                [
                    'company_id' => $i,
                    'currency_id' => $i,
                    'is_active' => true,
                ]
            );
        }
    }
}