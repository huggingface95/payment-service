<?php

namespace Database\Seeders;

use App\Models\PaymentBankRegion;
use Illuminate\Database\Seeder;

class PaymentBankRegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentBankRegion::firstOrCreate([
            'payment_bank_id' => 1,
            'region_id' => 1,
        ]);
    }
}
