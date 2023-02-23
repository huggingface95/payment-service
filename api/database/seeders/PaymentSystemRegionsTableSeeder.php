<?php

namespace Database\Seeders;

use App\Models\PaymentSystem;
use Illuminate\Database\Seeder;

class PaymentSystemRegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentSystem = PaymentSystem::find(1);

        $paymentSystem->regions()->attach(1);
    }
}
